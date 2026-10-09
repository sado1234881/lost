<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display messaging UI and load active chat partner if selected.
     */
    public function index(Request $request, $userId = null)
    {
        $currentUserId = Auth::id();
        $targetUserId = $userId ?: $request->input('to');
        $reportType = $request->input('report_type');
        $reportId = $request->input('report_id');

        // Fetch list of users that current user has exchanged messages with OR search target
        $conversations = $this->getConversationsList($currentUserId);

        $activeUser = null;
        $messages = collect();
        $reportContext = null;

        if ($targetUserId && $targetUserId != $currentUserId) {
            $activeUser = User::find($targetUserId);
            if ($activeUser) {
                // Ensure active user exists in conversations list if not present
                if (!$conversations->contains('id', $activeUser->id)) {
                    $conversations->prepend((object)[
                        'id' => $activeUser->id,
                        'name' => $activeUser->name,
                        'email' => $activeUser->email,
                        'role' => $activeUser->role,
                        'registration_number' => $activeUser->registration_number,
                        'last_message' => 'Start a conversation...',
                        'last_message_time' => null,
                        'unread_count' => 0,
                    ]);
                }

                // Mark unread messages from active user as read
                Message::where('sender_id', $activeUser->id)
                    ->where('receiver_id', $currentUserId)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);

                // Fetch message thread
                $messages = Message::where(function ($q) use ($currentUserId, $activeUser) {
                    $q->where('sender_id', $currentUserId)->where('receiver_id', $activeUser->id);
                })->orWhere(function ($q) use ($currentUserId, $activeUser) {
                    $q->where('sender_id', $activeUser->id)->where('receiver_id', $currentUserId);
                })->orderBy('created_at', 'asc')->get();
            }
        }

        // Fetch Report Context if report_type & report_id are passed
        if ($reportType && $reportId) {
            if ($reportType === 'found_item') {
                $reportContext = FoundItem::with('category')->find($reportId);
            } else {
                $reportContext = LostItem::with('category')->find($reportId);
            }
        }

        // List of all registered users for quick new chat lookup
        $allUsers = User::where('id', '!=', $currentUserId)->orderBy('name', 'asc')->get(['id', 'name', 'email', 'role', 'registration_number']);

        return view('messages.index', compact('conversations', 'activeUser', 'messages', 'allUsers', 'reportContext', 'reportType', 'reportId'));
    }

    /**
     * Fetch conversation thread via JSON for live auto-polling.
     */
    public function fetchMessages($userId)
    {
        $currentUserId = Auth::id();
        $activeUser = User::find($userId);

        if (!$activeUser) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Mark incoming messages as read
        Message::where('sender_id', $activeUser->id)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $messages = Message::where(function ($q) use ($currentUserId, $activeUser) {
            $q->where('sender_id', $currentUserId)->where('receiver_id', $activeUser->id);
        })->orWhere(function ($q) use ($currentUserId, $activeUser) {
            $q->where('sender_id', $activeUser->id)->where('receiver_id', $currentUserId);
        })->orderBy('created_at', 'asc')->get();

        $formattedMessages = $messages->map(function ($msg) use ($currentUserId) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'is_mine' => $msg->sender_id == $currentUserId,
                'message' => $msg->message,
                'report_type' => $msg->report_type,
                'report_id' => $msg->report_id,
                'created_at' => $msg->created_at->format('h:i A'),
                'date' => $msg->created_at->format('M d, Y'),
                'is_read' => $msg->is_read,
            ];
        });

        return response()->json([
            'success' => true,
            'activeUser' => [
                'id' => $activeUser->id,
                'name' => $activeUser->name,
                'role' => $activeUser->role,
                'email' => $activeUser->email,
            ],
            'messages' => $formattedMessages,
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
            'report_type' => 'nullable|string|in:lost_item,found_item',
            'report_id' => 'nullable|integer',
        ]);

        if ($request->receiver_id == Auth::id()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'You cannot send a message to yourself.'], 422);
            }
            return redirect()->back()->with('error', 'You cannot send a message to yourself.');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $validated['message'],
            'report_type' => $request->report_type,
            'report_id' => $request->report_id,
            'is_read' => false,
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'is_mine' => true,
                    'message' => $message->message,
                    'report_type' => $message->report_type,
                    'report_id' => $message->report_id,
                    'created_at' => $message->created_at->format('h:i A'),
                    'date' => $message->created_at->format('M d, Y'),
                    'is_read' => false,
                ],
            ]);
        }

        return redirect()->route('messages.index', ['user' => $request->receiver_id, 'report_type' => $request->report_type, 'report_id' => $request->report_id])
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Helper to retrieve recent conversation partners with last message metadata.
     */
    private function getConversationsList($userId)
    {
        $sentPartnerIds = Message::where('sender_id', $userId)->pluck('receiver_id');
        $receivedPartnerIds = Message::where('receiver_id', $userId)->pluck('sender_id');

        $partnerIds = $sentPartnerIds->merge($receivedPartnerIds)->unique();

        $conversations = collect();

        foreach ($partnerIds as $partnerId) {
            $partner = User::find($partnerId);
            if (!$partner) continue;

            $lastMessage = Message::where(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $userId)->where('receiver_id', $partnerId);
            })->orWhere(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $partnerId)->where('receiver_id', $userId);
            })->orderBy('created_at', 'desc')->first();

            $unreadCount = Message::where('sender_id', $partnerId)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $conversations->push((object)[
                'id' => $partner->id,
                'name' => $partner->name,
                'email' => $partner->email,
                'role' => $partner->role,
                'registration_number' => $partner->registration_number,
                'last_message' => $lastMessage ? \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', $lastMessage->message), 35) : '',
                'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                'last_message_date' => $lastMessage ? $lastMessage->created_at : null,
                'unread_count' => $unreadCount,
            ]);
        }

        return $conversations->sortByDesc('last_message_date')->values();
    }
}
