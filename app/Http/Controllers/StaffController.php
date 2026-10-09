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

class StaffController extends Controller
{
    /**
     * Staff Portal main landing & overview.
     */
    public function index()
    {
        $pendingLost = LostItem::with(['category', 'user'])->where('approval_status', 'pending_review')->latest()->get();
        $pendingFound = FoundItem::with(['category', 'user'])->where('approval_status', 'pending_review')->latest()->get();
        $pendingClaims = Claim::with(['user'])->where('claim_status', 'pending')->latest()->get();

        $stats = [
            'pending_approval' => $pendingLost->count() + $pendingFound->count(),
            'pending_claims' => $pendingClaims->count(),
            'total_found_logged' => FoundItem::count(),
            'total_released' => RecoveryHistory::count(),
        ];

        return view('staff.index', compact('pendingLost', 'pendingFound', 'pendingClaims', 'stats'));
    }

    /**
     * Staff Pending Reports queue tab.
     */
    public function pendingReports()
    {
        $pendingLost = LostItem::with(['category', 'user'])->where('approval_status', 'pending_review')->latest()->paginate(10, ['*'], 'lost_page');
        $pendingFound = FoundItem::with(['category', 'user'])->where('approval_status', 'pending_review')->latest()->paginate(10, ['*'], 'found_page');

        return view('staff.pending', compact('pendingLost', 'pendingFound'));
    }

    /**
     * Staff Property Intake Log tab (logging found property directly at security office).
     */
    public function propertyIntake()
    {
        $recentIntakes = FoundItem::with(['category', 'user'])->where('user_id', Auth::id())->latest()->paginate(10);
        return view('staff.intake', compact('recentIntakes'));
    }

    /**
     * Staff Claims Verification Queue tab.
     */
    public function claimsQueue()
    {
        $claims = Claim::with(['user'])->where('claim_status', 'pending')->latest()->paginate(15);
        return view('staff.claims', compact('claims'));
    }
}
