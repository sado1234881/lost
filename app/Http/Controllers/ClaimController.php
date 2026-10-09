<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\Notification;
use App\Models\RecoveryHistory;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClaimController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isStaff()) {
            $claims = Claim::with(['user'])->latest()->paginate(15);
        } else {
            $claims = Claim::with(['user'])->where('user_id', $user->id)->latest()->paginate(15);
        }

        return view('claims.index', compact('claims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:found_item,lost_item',
            'item_id' => 'required|integer',
            'proof_of_ownership' => 'required|string|min:10',
        ]);

        if ($request->item_type === 'found_item') {
            $item = FoundItem::findOrFail($request->item_id);
            if ($item->is_frozen) {
                return back()->with('error', 'This item is administratively frozen and claims cannot be submitted at this time.');
            }
        } else {
            $item = LostItem::findOrFail($request->item_id);
            if ($item->is_frozen) {
                return back()->with('error', 'This report is administratively frozen and claims cannot be submitted.');
            }
        }

        // Prevent duplicate pending claims for same item by same user
        $existing = Claim::where('user_id', Auth::id())
            ->where('item_type', $request->item_type)
            ->where('item_id', $request->item_id)
            ->whereIn('claim_status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have an active claim submitted for this item.');
        }

        $claim = Claim::create([
            'user_id' => Auth::id(),
            'item_type' => $request->item_type,
            'item_id' => $request->item_id,
            'proof_of_ownership' => $request->proof_of_ownership,
            'claim_date' => now(),
            'claim_status' => 'pending',
        ]);

        // Alert user
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Ownership Claim Submitted',
            'message' => 'Your claim for "' . $item->item_name . '" has been submitted for Security Verification.',
            'type' => 'claim',
            'action_url' => route('claims.show', $claim->id),
            'entity_type' => 'claim',
            'entity_id' => $claim->id,
        ]);

        AuditService::log('claim_submitted', 'Claim', $claim->id, null, $claim->toArray());

        return redirect()->route('claims.show', $claim->id)
            ->with('success', 'Ownership claim submitted successfully. Security staff will review your proof.');
    }

    public function show($id)
    {
        $claim = Claim::with(['user', 'review'])->findOrFail($id);

        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff() && Auth::id() !== $claim->user_id) {
            abort(403, 'Unauthorized access.');
        }

        $item = $claim->item;

        return view('claims.show', compact('claim', 'item'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Only Security Staff or Administrators can review claims.');
        }

        $request->validate([
            'claim_status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $claim = Claim::findOrFail($id);
        $oldStatus = $claim->claim_status;
        $claim->claim_status = $request->claim_status;
        $claim->admin_notes = $request->admin_notes;

        if ($request->claim_status === 'approved') {
            $claim->qr_code_token = 'CLAIM-' . Str::upper(Str::random(12));

            // Mark found item status as claimed
            if ($claim->item_type === 'found_item') {
                $foundItem = FoundItem::find($claim->item_id);
                if ($foundItem) {
                    $foundItem->status = 'claimed';
                    $foundItem->save();
                }
            }

            Notification::create([
                'user_id' => $claim->user_id,
                'title' => 'Ownership Claim APPROVED!',
                'message' => 'Security Admin has approved your claim. Present your QR Code token (' . $claim->qr_code_token . ') at the Security Office to collect your property.',
                'type' => 'claim',
                'action_url' => route('claims.show', $claim->id),
                'entity_type' => 'claim',
                'entity_id' => $claim->id,
            ]);

            AuditService::log('claim_approved', 'Claim', $claim->id, ['status' => $oldStatus], ['status' => 'approved', 'qr_token' => $claim->qr_code_token]);
        } else {
            Notification::create([
                'user_id' => $claim->user_id,
                'title' => 'Ownership Claim Declined',
                'message' => 'Your ownership claim for ' . ($claim->item->item_name ?? 'item') . ' was declined. Reason: ' . ($request->admin_notes ?? 'Insufficient proof provided.'),
                'type' => 'claim',
                'action_url' => route('claims.show', $claim->id),
                'entity_type' => 'claim',
                'entity_id' => $claim->id,
            ]);

            AuditService::log('claim_rejected', 'Claim', $claim->id, ['status' => $oldStatus], ['status' => 'rejected', 'reason' => $request->admin_notes]);
        }

        $claim->save();

        return redirect()->route('claims.show', $claim->id)
            ->with('success', 'Claim status updated to ' . strtoupper($request->claim_status) . '.');
    }

    public function showQr($id)
    {
        $claim = Claim::findOrFail($id);

        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff() && Auth::id() !== $claim->user_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($claim->claim_status !== 'approved' && $claim->claim_status !== 'released' && $claim->claim_status !== 'closed') {
            return redirect()->route('claims.show', $claim->id)
                ->with('error', 'QR Release code is only available for approved claims.');
        }

        $item = $claim->item;

        return view('claims.qr', compact('claim', 'item'));
    }

    public function showReleasePage()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Only Staff or Administrators can access property release scanner.');
        }

        return view('admin.release');
    }

    public function verifyRelease(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Only Staff or Administrators can authorize property release.');
        }

        $request->validate([
            'qr_code_token' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $claim = Claim::where('qr_code_token', trim($request->qr_code_token))->first();

        if (!$claim) {
            return back()->with('error', 'Invalid QR Release token. Record not found.');
        }

        if ($claim->claim_status === 'released' || $claim->claim_status === 'closed') {
            return back()->with('error', 'This property has already been released on ' . $claim->updated_at->format('M d, Y H:i'));
        }

        if ($claim->claim_status !== 'approved') {
            return back()->with('error', 'This claim has not been approved for release yet.');
        }

        // Complete release
        $claim->claim_status = 'released';
        $claim->save();

        $item = $claim->item;
        $itemName = $item ? $item->item_name : 'Unknown Item';
        $categoryName = ($item && $item->category) ? $item->category->category_name : 'General';
        $finderId = ($item && isset($item->user_id)) ? $item->user_id : null;

        if ($claim->item_type === 'found_item' && $item) {
            $item->status = 'recovered';
            $item->save();

            // Update associated QR tag status to recovered
            if (method_exists($item, 'qrCode') && $item->qrCode) {
                $item->qrCode->update(['status' => 'recovered']);
            }
        }

        // Record in Recovery History table
        RecoveryHistory::create([
            'claim_id' => $claim->id,
            'item_name' => $itemName,
            'category_name' => $categoryName,
            'owner_id' => $claim->user_id,
            'finder_id' => $finderId,
            'recovery_date' => now(),
            'verification_method' => 'QR Code Release Verification',
            'remarks' => $request->remarks ?? 'Verified and released by Security Staff at Security Desk.',
        ]);

        // Send final notification + SMS requesting feedback
        Notification::create([
            'user_id' => $claim->user_id,
            'title' => 'Property Handover Complete — Feedback Requested',
            'message' => 'Your property "' . $itemName . '" has been safely handed over. Please click to confirm outcome and leave a review.',
            'type' => 'system',
            'action_url' => route('claims.show', $claim->id),
            'entity_type' => 'claim',
            'entity_id' => $claim->id,
        ]);

        AuditService::log('claim_released', 'Claim', $claim->id, ['status' => 'approved'], ['status' => 'released']);

        return redirect()->route('recovery.index')
            ->with('success', 'Property successfully verified and released to owner! Recovery history record created.');
    }
}
