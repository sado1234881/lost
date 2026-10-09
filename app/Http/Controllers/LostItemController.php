<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\Category;
use App\Models\Notification;
use App\Services\AuditService;
use App\Services\MatchEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = LostItem::with(['category', 'user', 'matchedFoundItem']);

        // Normal users only see approved items OR their own submitted items
        if (!$user || (!$user->isAdmin() && !$user->isStaff())) {
            $query->where(function ($q) use ($user) {
                $q->where('approval_status', 'approved');
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            });
        } elseif ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_lost', 'like', "%{$search}%");
            });
        }

        if ($request->filled('color')) {
            $query->where('color', 'like', "%{$request->color}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_lost', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_lost', '<=', $request->date_to);
        }

        $lostItems = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('lost_items.index', compact('lostItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('lost_items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_name' => 'required|string|max:255',
            'color' => 'nullable|string|max:100',
            'description' => 'required|string|min:10',
            'date_lost' => 'required|date|before_or_equal:today',
            'location_lost' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lost_items', 'public');
        }

        $lostItem = LostItem::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'item_name' => $request->item_name,
            'color' => $request->color,
            'description' => $request->description,
            'date_lost' => $request->date_lost,
            'location_lost' => $request->location_lost,
            'image' => $imagePath,
            'status' => 'active',
            'approval_status' => 'pending_review',
        ]);

        $reportCode = 'LF-L' . str_pad($lostItem->id, 4, '0', STR_PAD_LEFT);

        // Alert user
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Lost Item Report Submitted (Pending Review)',
            'message' => "Your Lost Property Report #{$reportCode} ('{$lostItem->item_name}') has been submitted and is awaiting security administration review.",
            'type' => 'approval',
            'action_url' => route('lost-items.show', $lostItem->id),
            'entity_type' => 'lost_item',
            'entity_id' => $lostItem->id,
        ]);

        // Audit Log
        AuditService::log('report_submitted', 'LostItem', $lostItem->id, null, $lostItem->toArray());

        return redirect()->route('lost-items.show', $lostItem->id)
            ->with('success', "Your report #{$reportCode} was submitted successfully and is awaiting administrative approval.");
    }

    public function show($id)
    {
        $lostItem = LostItem::with(['category', 'user', 'frozenBy', 'matchedFoundItem'])->findOrFail($id);

        // Find potential matches if item is active/approved
        $potentialMatches = collect();
        if ($lostItem->approval_status === 'approved') {
            $potentialMatches = MatchEngineService::findMatchesForLostItem($lostItem);
        }

        return view('lost_items.show', compact('lostItem', 'potentialMatches'));
    }

    public function approve($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Unauthorized administrative action.');
        }

        $lostItem = LostItem::findOrFail($id);
        $oldStatus = $lostItem->approval_status;

        $lostItem->approval_status = 'approved';
        $lostItem->rejection_reason = null;
        $lostItem->save();

        $reportCode = 'LF-L' . str_pad($lostItem->id, 4, '0', STR_PAD_LEFT);

        // Notify user via In-App Alert + SMS
        Notification::create([
            'user_id' => $lostItem->user_id,
            'title' => 'Lost Item Report APPROVED',
            'message' => "Your lost item report #{$reportCode} ('{$lostItem->item_name}') was approved by security staff and is now active on the system.",
            'type' => 'approval',
            'action_url' => route('lost-items.show', $lostItem->id),
            'entity_type' => 'lost_item',
            'entity_id' => $lostItem->id,
        ]);

        // Auto Match Discovery
        MatchEngineService::processAutoMatching($lostItem);

        // Audit Log
        AuditService::log('report_approved', 'LostItem', $lostItem->id, ['approval_status' => $oldStatus], ['approval_status' => 'approved']);

        return redirect()->back()->with('success', "Report #{$reportCode} has been approved and published.");
    }

    public function reject(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Unauthorized administrative action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ]);

        $lostItem = LostItem::findOrFail($id);
        $oldStatus = $lostItem->approval_status;

        $lostItem->approval_status = 'rejected';
        $lostItem->rejection_reason = $request->rejection_reason;
        $lostItem->save();

        $reportCode = 'LF-L' . str_pad($lostItem->id, 4, '0', STR_PAD_LEFT);

        // Alert + SMS
        Notification::create([
            'user_id' => $lostItem->user_id,
            'title' => 'Report Rejection Notice',
            'message' => "Your report #{$reportCode} was rejected by Admin. Reason: {$request->rejection_reason}",
            'type' => 'rejection',
            'action_url' => route('lost-items.show', $lostItem->id),
            'entity_type' => 'lost_item',
            'entity_id' => $lostItem->id,
        ]);

        AuditService::log('report_rejected', 'LostItem', $lostItem->id, ['approval_status' => $oldStatus], ['approval_status' => 'rejected', 'reason' => $request->rejection_reason]);

        return redirect()->back()->with('success', "Report #{$reportCode} has been rejected with feedback provided to user.");
    }

    public function freeze(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only Administrators can freeze records.');
        }

        $request->validate([
            'freeze_reason' => 'required|string|min:5',
        ]);

        $lostItem = LostItem::findOrFail($id);
        $lostItem->is_frozen = true;
        $lostItem->freeze_reason = $request->freeze_reason;
        $lostItem->frozen_at = now();
        $lostItem->frozen_by = Auth::id();
        $lostItem->save();

        // Alert owner
        Notification::create([
            'user_id' => $lostItem->user_id,
            'title' => 'Report Administratively Frozen',
            'message' => "Your report for '{$lostItem->item_name}' has been frozen by Administrator. Reason: {$request->freeze_reason}",
            'type' => 'freeze',
            'action_url' => route('lost-items.show', $lostItem->id),
            'entity_type' => 'lost_item',
            'entity_id' => $lostItem->id,
        ]);

        AuditService::log('item_frozen', 'LostItem', $lostItem->id, ['is_frozen' => false], ['is_frozen' => true, 'reason' => $request->freeze_reason]);

        return redirect()->back()->with('success', "Record has been locked and administratively frozen.");
    }

    public function unfreeze($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only Administrators can unfreeze records.');
        }

        $lostItem = LostItem::findOrFail($id);
        $lostItem->is_frozen = false;
        $lostItem->freeze_reason = null;
        $lostItem->frozen_at = null;
        $lostItem->frozen_by = null;
        $lostItem->save();

        Notification::create([
            'user_id' => $lostItem->user_id,
            'title' => 'Report Unfrozen',
            'message' => "Administrative lock on your report '{$lostItem->item_name}' has been removed.",
            'type' => 'freeze',
            'action_url' => route('lost-items.show', $lostItem->id),
            'entity_type' => 'lost_item',
            'entity_id' => $lostItem->id,
        ]);

        AuditService::log('item_unfrozen', 'LostItem', $lostItem->id, ['is_frozen' => true], ['is_frozen' => false]);

        return redirect()->back()->with('success', "Administrative lock removed successfully.");
    }

    public function destroy($id)
    {
        $lostItem = LostItem::findOrFail($id);

        if ($lostItem->is_frozen && !Auth::user()->isAdmin()) {
            abort(403, 'This report is administratively frozen and cannot be deleted.');
        }

        if (!Auth::user()->isAdmin() && Auth::id() !== $lostItem->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($lostItem->image) {
            Storage::disk('public')->delete($lostItem->image);
        }

        $itemId = $lostItem->id;
        $itemName = $lostItem->item_name;
        $lostItem->delete();

        AuditService::log('report_deleted', 'LostItem', $itemId, ['item_name' => $itemName], null);

        return redirect()->route('lost-items.index')
            ->with('success', 'Lost item report deleted.');
    }
}
