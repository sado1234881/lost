<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\Claim;
use App\Models\RecoveryHistory;
use App\Services\MatchEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminWorkspace();
        }

        if ($user->isStaff()) {
            return app(StaffController::class)->index();
        }

        return $this->userWorkspace($user);
    }

    private function adminWorkspace()
    {
        $totalUsers = User::count();
        $totalLost = LostItem::count();
        $totalFound = FoundItem::count();
        $totalRecovered = RecoveryHistory::count();
        $totalPendingClaims = Claim::where('claim_status', 'pending')->count();
        $totalMessages = \App\Models\Message::count();

        // Detailed Admin Operational Metrics
        $pendingApprovalLost = LostItem::where('approval_status', 'pending_review')->count();
        $pendingApprovalFound = FoundItem::where('approval_status', 'pending_review')->count();
        $totalPendingApproval = $pendingApprovalLost + $pendingApprovalFound;

        $approvedReports = LostItem::where('approval_status', 'approved')->count() + FoundItem::where('approval_status', 'approved')->count();
        $rejectedReports = LostItem::where('approval_status', 'rejected')->count() + FoundItem::where('approval_status', 'rejected')->count();

        $frozenItems = FoundItem::where('is_frozen', true)->count() + LostItem::where('is_frozen', true)->count();
        $resolvedCases = RecoveryHistory::count();
        $unresolvedCases = LostItem::where('status', 'active')->count() + FoundItem::where('status', 'reported')->count();
        $pendingUserActions = User::where('action_required', true)->count();

        // Recovery Rate
        $totalReported = $totalLost + $totalFound;
        $recoveryRate = $totalReported > 0 ? round(($totalRecovered / $totalReported) * 100, 1) : 0;

        // Categories distribution for Pie Chart
        $categoriesStats = Category::withCount(['lostItems', 'foundItems'])->get()->map(function ($cat) {
            return [
                'name' => $cat->category_name,
                'count' => $cat->lost_items_count + $cat->found_items_count,
            ];
        });

        // Monthly statistics (Last 6 months)
        $months = [];
        $monthlyLost = [];
        $monthlyFound = [];
        $monthlyRecovered = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabel = $date->format('M Y');
            $year = $date->year;
            $monthNum = $date->month;

            $months[] = $monthLabel;
            $monthlyLost[] = LostItem::whereYear('created_at', $year)->whereMonth('created_at', $monthNum)->count();
            $monthlyFound[] = FoundItem::whereYear('created_at', $year)->whereMonth('created_at', $monthNum)->count();
            $monthlyRecovered[] = RecoveryHistory::whereYear('recovery_date', $year)->whereMonth('recovery_date', $monthNum)->count();
        }

        // Top Frequently Lost Categories
        $topLostCategories = Category::withCount('lostItems')
            ->orderBy('lost_items_count', 'desc')
            ->take(5)
            ->get();

        // Recent items & messages
        $recentLost = LostItem::with(['category', 'user'])->latest()->take(5)->get();
        $recentFound = FoundItem::with(['category', 'user'])->latest()->take(5)->get();
        $recentClaims = Claim::with(['user'])->where('claim_status', 'pending')->latest()->take(5)->get();
        $recentMessages = \App\Models\Message::with(['sender', 'receiver'])->latest()->take(5)->get();

        return view('workspace.admin', compact(
            'totalUsers',
            'totalLost',
            'totalFound',
            'totalRecovered',
            'totalPendingClaims',
            'totalMessages',
            'totalPendingApproval',
            'approvedReports',
            'rejectedReports',
            'frozenItems',
            'resolvedCases',
            'unresolvedCases',
            'pendingUserActions',
            'recoveryRate',
            'categoriesStats',
            'months',
            'monthlyLost',
            'monthlyFound',
            'monthlyRecovered',
            'topLostCategories',
            'recentLost',
            'recentFound',
            'recentClaims',
            'recentMessages'
        ));
    }

    private function userWorkspace($user)
    {
        $myLostCount = LostItem::where('user_id', $user->id)->count();
        $myFoundCount = FoundItem::where('user_id', $user->id)->count();
        $myClaimsCount = Claim::where('user_id', $user->id)->count();
        $myActiveClaims = Claim::where('user_id', $user->id)->where('claim_status', 'approved')->count();

        $myLostItems = LostItem::with(['category', 'matchedFoundItem'])->where('user_id', $user->id)->latest()->get();
        $myFoundItems = FoundItem::with(['category', 'matchedLostItem'])->where('user_id', $user->id)->latest()->get();
        $myClaims = Claim::with('item')->where('user_id', $user->id)->latest()->get();

        $activeReportsCount = LostItem::where('user_id', $user->id)->where('approval_status', 'approved')->where('status', 'active')->count()
            + FoundItem::where('user_id', $user->id)->where('approval_status', 'approved')->where('status', 'reported')->count();

        $pendingReportsCount = LostItem::where('user_id', $user->id)->where('approval_status', 'pending_review')->count()
            + FoundItem::where('user_id', $user->id)->where('approval_status', 'pending_review')->count();

        $resolvedItemsCount = LostItem::where('user_id', $user->id)->where('status', 'closed')->count()
            + FoundItem::where('user_id', $user->id)->where('status', 'closed')->count()
            + Claim::where('user_id', $user->id)->where('claim_status', 'closed')->count();

        // Recent found items matching user's lost categories
        $recentFoundMatches = FoundItem::with('category')
            ->where('approval_status', 'approved')
            ->where('status', 'reported')
            ->latest()
            ->take(6)
            ->get();

        return view('workspace.user', compact(
            'myLostCount',
            'myFoundCount',
            'myClaimsCount',
            'myActiveClaims',
            'activeReportsCount',
            'pendingReportsCount',
            'resolvedItemsCount',
            'myLostItems',
            'myFoundItems',
            'myClaims',
            'recentFoundMatches'
        ));
    }
}
