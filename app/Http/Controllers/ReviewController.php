<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\Notification;
use App\Models\Review;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'rating' => 'required|integer|min:1|max:5',
            'returned_successfully' => 'required|boolean',
            'comments' => 'nullable|string|max:1000',
        ]);

        $claim = Claim::findOrFail($request->claim_id);

        if (!Auth::user()->isAdmin() && Auth::id() !== $claim->user_id) {
            abort(403, 'Unauthorized feedback submission.');
        }

        if ($claim->claim_status !== 'released' && $claim->claim_status !== 'closed') {
            return redirect()->back()->with('error', 'Feedback and reviews can only be submitted after property handover is completed.');
        }

        // Prevent duplicate review for same claim
        $existing = Review::where('claim_id', $claim->id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'You have already submitted a review for this completed case.');
        }

        $review = Review::create([
            'claim_id' => $claim->id,
            'user_id' => Auth::id(),
            'item_type' => $claim->item_type,
            'item_id' => $claim->item_id,
            'rating' => $request->rating,
            'returned_successfully' => $request->returned_successfully,
            'comments' => $request->comments,
        ]);

        // Transition claim status to closed
        $claim->claim_status = 'closed';
        $claim->save();

        if ($claim->item_type === 'found_item') {
            $item = FoundItem::find($claim->item_id);
            if ($item) {
                $item->status = 'closed';
                $item->save();
            }
        } else {
            $item = LostItem::find($claim->item_id);
            if ($item) {
                $item->status = 'closed';
                $item->save();
            }
        }

        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Case Formally Closed',
            'message' => 'Thank you for providing feedback! Your Lost & Found case has been formally marked as closed.',
            'type' => 'system',
            'action_url' => route('claims.show', $claim->id),
            'entity_type' => 'claim',
            'entity_id' => $claim->id,
        ]);

        AuditService::log('case_closed', 'Claim', $claim->id, ['status' => 'released'], ['status' => 'closed', 'rating' => $request->rating]);

        return redirect()->route('claims.show', $claim->id)
            ->with('success', 'Thank you for your feedback! The recovery case has been marked as closed.');
    }
}
