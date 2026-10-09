<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Record an administrative or security audit trail entry.
     *
     * @param string $action Action name (e.g. 'report_approved', 'item_frozen', 'claim_released')
     * @param string|null $targetType Model class or target descriptor
     * @param int|null $targetId Model ID
     * @param array|null $oldValues Old state attributes
     * @param array|null $newValues New state attributes
     * @return AuditLog
     */
    public static function log(string $action, ?string $targetType = null, ?int $targetId = null, ?array $oldValues = null, ?array $newValues = null): AuditLog
    {
        $user = Auth::user();

        return AuditLog::create([
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'System/Guest',
            'user_role' => $user ? $user->role : 'guest',
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
        ]);
    }
}
