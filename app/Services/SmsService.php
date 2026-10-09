<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * SmsService (Legacy Shim)
 * 
 * Note: External SMS delivery has been replaced with the centralized
 * In-System Notification system. This service is retained as a safe,
 * non-breaking no-op wrapper for backwards compatibility.
 */
class SmsService
{
    /**
     * Legacy send method. Does not dispatch external cellular SMS.
     *
     * @param User|string $recipient User model or phone number string
     * @param string $message Text content of the notification
     * @return SmsLog
     */
    public static function send($recipient, string $message): SmsLog
    {
        $userId = null;
        $phoneNumber = 'in-system';

        if ($recipient instanceof User) {
            $userId = $recipient->id;
            $phoneNumber = $recipient->phone_number ?? 'in-system';
        } elseif (is_string($recipient)) {
            $phoneNumber = $recipient;
        }

        Log::info("[In-System Notice Dispatch for user {$userId}]: {$message}");

        return new SmsLog([
            'user_id' => $userId,
            'phone_number' => $phoneNumber,
            'message' => $message,
            'status' => 'handled_in_system',
        ]);
    }
}
