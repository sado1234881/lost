<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Models\Category;
use App\Models\Notification;
use App\Services\AuditService;
use App\Services\MatchEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoundItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = FoundItem::with(['category', 'user', 'matchedLostItem']);

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
                  ->orWhere('location_found', 'like', "%{$search}%");
            });
        }

        if ($request->filled('color')) {
            $query->where('color', 'like', "%{$request->color}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_found', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_found', '<=', $request->date_to);
        }

        $foundItems = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('found_items.index', compact('foundItems', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('found_items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_name' => 'required|string|max:255',
            'color' => 'nullable|string|max:100',
            'description' => 'required|string|min:10',
            'date_found' => 'required|date|before_or_equal:today',
            'location_found' => 'required|string|max:255',
            'storage_location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('found_items', 'public');
        }

        $foundItem = FoundItem::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'item_name' => $request->item_name,
            'color' => $request->color,
            'description' => $request->description,
            'date_found' => $request->date_found,
            'location_found' => $request->location_found,
            'storage_location' => $request->storage_location,
            'image' => $imagePath,
            'status' => 'reported',
            'approval_status' => 'pending_review',
        ]);

        $reportCode = 'LF-F' . str_pad($foundItem->id, 4, '0', STR_PAD_LEFT);

        // Clear action compliance requirement if present on user
        $user = Auth::user();
        if ($user->action_required) {
            $user->update([
                'action_required' => false,
                'action_required_message' => null,
                'action_required_url' => null,
            ]);
        }

        // Send In-App Alert
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Found Item Report Submitted (Pending Review)',
            'message' => "Thank you for reporting found property #{$reportCode} ('{$foundItem->item_name}'). Your report is logged and awaiting security approval.",
            'type' => 'approval',
            'action_url' => route('found-items.show', $foundItem->id),
            'entity_type' => 'found_item',
            'entity_id' => $foundItem->id,
        ]);

        // Audit Log
        AuditService::log('report_submitted', 'FoundItem', $foundItem->id, null, $foundItem->toArray());

        return redirect()->route('found-items.show', $foundItem->id)
            ->with('success', "Thank you! Found item report #{$reportCode} submitted successfully for administrative review.");
    }

    public function show($id)
    {
        $foundItem = FoundItem::with(['category', 'user', 'claims.user', 'frozenBy', 'matchedLostItem'])->findOrFail($id);

        // Find potential matching lost items if item is active/approved
        $potentialMatches = collect();
        if ($foundItem->approval_status === 'approved') {
            $potentialMatches = MatchEngineService::findMatchesForFoundItem($foundItem);
        }

        return view('found_items.show', compact('foundItem', 'potentialMatches'));
    }

    public function approve($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Unauthorized administrative action.');
        }

        $foundItem = FoundItem::findOrFail($id);
        $oldStatus = $foundItem->approval_status;

        $foundItem->approval_status = 'approved';
        $foundItem->rejection_reason = null;
        $foundItem->save();

        $reportCode = 'LF-F' . str_pad($foundItem->id, 4, '0', STR_PAD_LEFT);

        Notification::create([
            'user_id' => $foundItem->user_id,
            'title' => 'Found Property Report APPROVED',
            'message' => "Your found item report #{$reportCode} ('{$foundItem->item_name}') has been approved and published to the public registry.",
            'type' => 'approval',
            'action_url' => route('found-items.show', $foundItem->id),
            'entity_type' => 'found_item',
            'entity_id' => $foundItem->id,
        ]);

        // Run auto-matching
        MatchEngineService::processAutoMatching($foundItem);

        AuditService::log('report_approved', 'FoundItem', $foundItem->id, ['approval_status' => $oldStatus], ['approval_status' => 'approved']);

        return redirect()->back()->with('success', "Found item report #{$reportCode} has been approved.");
    }

    public function reject(Request $request, $id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isStaff()) {
            abort(403, 'Unauthorized administrative action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ]);

        $foundItem = FoundItem::findOrFail($id);
        $oldStatus = $foundItem->approval_status;

        $foundItem->approval_status = 'rejected';
        $foundItem->rejection_reason = $request->rejection_reason;
        $foundItem->save();

        $reportCode = 'LF-F' . str_pad($foundItem->id, 4, '0', STR_PAD_LEFT);

        Notification::create([
            'user_id' => $foundItem->user_id,
            'title' => 'Found Item Report Rejected',
            'message' => "Your found item report #{$reportCode} was declined by Admin. Reason: {$request->rejection_reason}",
            'type' => 'rejection',
            'action_url' => route('found-items.show', $foundItem->id),
            'entity_type' => 'found_item',
            'entity_id' => $foundItem->id,
        ]);

        AuditService::log('report_rejected', 'FoundItem', $foundItem->id, ['approval_status' => $oldStatus], ['approval_status' => 'rejected', 'reason' => $request->rejection_reason]);

        return redirect()->back()->with('success', "Report #{$reportCode} has been rejected with user feedback.");
    }

    public function freeze(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only Security Administrators can freeze found items.');
        }

        $request->validate([
            'freeze_reason' => 'required|string|min:5',
        ]);

        $foundItem = FoundItem::findOrFail($id);
        $foundItem->is_frozen = true;
        $foundItem->freeze_reason = $request->freeze_reason;
        $foundItem->frozen_at = now();
        $foundItem->frozen_by = Auth::id();
        $foundItem->save();

        Notification::create([
            'user_id' => $foundItem->user_id,
            'title' => 'Found Item Record FROZEN by Security Admin',
            'message' => "This found property record '{$foundItem->item_name}' has been locked under security administrative hold. Reason: {$request->freeze_reason}",
            'type' => 'freeze',
            'action_url' => route('found-items.show', $foundItem->id),
            'entity_type' => 'found_item',
            'entity_id' => $foundItem->id,
        ]);

        AuditService::log('item_frozen', 'FoundItem', $foundItem->id, ['is_frozen' => false], ['is_frozen' => true, 'reason' => $request->freeze_reason]);

        return redirect()->back()->with('success', "Found item record is now locked and frozen.");
    }

    public function unfreeze($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only Security Administrators can unfreeze items.');
        }

        $foundItem = FoundItem::findOrFail($id);
        $foundItem->is_frozen = false;
        $foundItem->freeze_reason = null;
        $foundItem->frozen_at = null;
        $foundItem->frozen_by = null;
        $foundItem->save();

        Notification::create([
            'user_id' => $foundItem->user_id,
            'title' => 'Found Item Administrative Hold Lifted',
            'message' => "The administrative freeze lock on '{$foundItem->item_name}' has been removed.",
            'type' => 'freeze',
            'action_url' => route('found-items.show', $foundItem->id),
            'entity_type' => 'found_item',
            'entity_id' => $foundItem->id,
        ]);

        AuditService::log('item_unfrozen', 'FoundItem', $foundItem->id, ['is_frozen' => true], ['is_frozen' => false]);

        return redirect()->back()->with('success', "Administrative lock on found item removed.");
    }

    public function destroy($id)
    {
        $foundItem = FoundItem::findOrFail($id);

        if ($foundItem->is_frozen && !Auth::user()->isAdmin()) {
            abort(403, 'This item is administratively frozen and locked against deletion.');
        }

        if (!Auth::user()->isAdmin() && Auth::id() !== $foundItem->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($foundItem->image) {
            Storage::disk('public')->delete($foundItem->image);
        }

        $itemId = $foundItem->id;
        $itemName = $foundItem->item_name;
        $foundItem->delete();

        AuditService::log('report_deleted', 'FoundItem', $itemId, ['item_name' => $itemName], null);

        return redirect()->route('found-items.index')
            ->with('success', 'Found item record deleted.');
    }
}
