<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSecurityController extends Controller
{
    /**
     * Display Security & Audit Center.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only Administrators can access Security & Audit Center.');
        }

        $tab = $request->input('tab', 'audit'); // 'audit', 'notifications' (or legacy 'sms'), 'users'
        if ($tab === 'sms') {
            $tab = 'notifications';
        }

        $auditLogs = AuditLog::with('user')->latest()->paginate(15, ['*'], 'audit_page')->withQueryString();
        $notifications = Notification::with('user')->latest()->paginate(15, ['*'], 'notif_page')->withQueryString();
        $unverifiedUsers = User::whereNull('email_verified_at')->latest()->get();

        $stats = [
            'total_audits' => AuditLog::count(),
            'total_notifications' => Notification::count(),
            'unread_notifications' => Notification::where('is_read', false)->count(),
            'unverified_accounts' => $unverifiedUsers->count(),
        ];

        return view('admin.security.index', compact('auditLogs', 'notifications', 'unverifiedUsers', 'stats', 'tab'));
    }
}
