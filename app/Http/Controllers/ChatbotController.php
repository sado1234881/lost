<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Process a chatbot message query and return an intelligent, structured response.
     */
    public function query(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'reply' => "Hello! How can I assist you today with the SJUT Lost & Found system?",
                'actions' => $this->getDefaultActions(),
            ]);
        }

        $response = $this->matchKnowledgeBase(strtolower($message));

        return response()->json([
            'success' => true,
            'reply' => $response['reply'],
            'actions' => $response['actions'] ?? [],
            'chips' => $response['chips'] ?? $this->getDefaultChips(),
        ]);
    }

    /**
     * Match user input against system knowledge base intents.
     */
    private function matchKnowledgeBase(string $q): array
    {
        // 1. Report Lost Item
        if (Str::contains($q, ['lost', 'misplace', 'missing', 'i lost', 'report lost', 'lost something', 'stolen'])) {
            return [
                'reply' => "To report an item you lost on campus:\n\n"
                    . "1. Click the **'Report Lost Item'** button.\n"
                    . "2. Choose the correct category (Electronics, IDs, Books, etc.).\n"
                    . "3. Provide specific identifying marks, color, and model.\n"
                    . "4. State where and when you lost it.\n"
                    . "5. Once submitted, our team reviews it and the system automatically searches for matches with found items!",
                'actions' => [
                    ['label' => 'Report Lost Item', 'url' => route('lost-items.create'), 'icon' => 'fa-circle-exclamation', 'primary' => true],
                    ['label' => 'Browse Found Items', 'url' => route('found-items.index'), 'icon' => 'fa-boxes-stacked'],
                ],
                'chips' => ['How does matching work?', 'How do I claim an item?', 'Download System Guide'],
            ];
        }

        // 2. Report Found Item
        if (Str::contains($q, ['found', 'picked', 'picked up', 'saw an item', 'turn in', 'report found', 'i found'])) {
            return [
                'reply' => "Thank you for helping our SJUT community! To report a found item:\n\n"
                    . "1. Click **'Report Found Item'**.\n"
                    . "2. Provide item details, where you found it, and the date.\n"
                    . "3. Choose custody status: state whether you are holding it temporarily or have surrendered it to the Security Desk.\n"
                    . "4. Hand it over to the Security Central Office within 24-48 hours for safekeeping.",
                'actions' => [
                    ['label' => 'Report Found Item', 'url' => route('found-items.create'), 'icon' => 'fa-hand-holding-hand', 'primary' => true],
                    ['label' => 'Security Office Location', 'url' => route('landing') . '#about-sjut', 'icon' => 'fa-shield-halved'],
                ],
                'chips' => ['Where is the Security Desk?', 'How does claiming work?', 'Download System Guide'],
            ];
        }

        // 3. Search / Browse / Find
        if (Str::contains($q, ['search', 'browse', 'look for', 'find my', 'list', 'check', 'directory', 'filter'])) {
            return [
                'reply' => "You can browse active campus records anytime:\n\n"
                    . "• **Browse Found Items**: Inspect items handed in or reported across campus.\n"
                    . "• **Browse Lost Items**: See items other community members are looking for.\n\n"
                    . "Use category filters and keywords (like phone brand, color, ID number) to narrow your search.",
                'actions' => [
                    ['label' => 'Browse Found Items', 'url' => route('found-items.index'), 'icon' => 'fa-boxes-stacked', 'primary' => true],
                    ['label' => 'Browse Lost Items', 'url' => route('lost-items.index'), 'icon' => 'fa-list-check'],
                ],
                'chips' => ['How do I report a lost item?', 'How do I claim an item?', 'Contact Security'],
            ];
        }

        // 4. Claiming & Verification Process
        if (Str::contains($q, ['claim', 'proof', 'ownership', 'verify', 'verification', 'qr', 'release', 'collect', 'retrieve', 'pick up'])) {
            return [
                'reply' => "Here is how the official claiming and verification process works:\n\n"
                    . "1. Find your item in the **'Browse Found Items'** registry.\n"
                    . "2. Click **'Claim This Item'** on its details page.\n"
                    . "3. Provide verifiable proof of ownership (serial number, lock pattern, unique scratch, or invoice).\n"
                    . "4. Campus Security reviews your claim.\n"
                    . "5. Once approved, you receive a digital **QR Release Pass** to present along with your University ID at the Security Desk!",
                'actions' => [
                    ['label' => 'Browse Found Items', 'url' => route('found-items.index'), 'icon' => 'fa-boxes-stacked', 'primary' => true],
                    ['label' => 'Download Full Guide (PDF)', 'url' => route('guide.download'), 'icon' => 'fa-file-arrow-down'],
                ],
                'chips' => ['What is a QR Release Pass?', 'Where is Security Desk?', 'How to report lost item'],
            ];
        }

        // 5. Account, Login, Register & Password
        if (Str::contains($q, ['account', 'login', 'sign in', 'register', 'sign up', 'password', 'otp', 'verification code', 'email'])) {
            return [
                'reply' => "Managing your account is simple:\n\n"
                    . "• **New User?** Click **'Create Account'** to register using your email address and verify with a quick OTP code.\n"
                    . "• **Existing User?** Click **'Sign In'** to access My Workspace, track reports, and manage claims.\n"
                    . "• All status updates and match alerts will appear in your in-system notifications.",
                'actions' => [
                    ['label' => 'Sign In', 'url' => route('login'), 'icon' => 'fa-right-to-bracket', 'primary' => true],
                    ['label' => 'Create Account', 'url' => route('register'), 'icon' => 'fa-user-plus'],
                ],
                'chips' => ['How do I report a lost item?', 'Where is Security Desk?', 'Download System Guide'],
            ];
        }

        // 6. Notifications & Alerts (System notifications instead of SMS)
        if (Str::contains($q, ['notification', 'alert', 'sms', 'message', 'bell', 'update', 'status', 'email notification'])) {
            return [
                'reply' => "All updates are delivered directly via **In-System Notifications & Alerts**:\n\n"
                    . "• You receive alerts when: your report is approved or reviewed, a potential match is discovered, or your claim status changes.\n"
                    . "• Click the **bell icon** in the top navigation bar to view your alerts anytime.\n"
                    . "• You can also communicate securely with finders using integrated **Direct Messages**.",
                'actions' => [
                    ['label' => 'View Notifications', 'url' => route('notifications.index'), 'icon' => 'fa-bell', 'primary' => true],
                    ['label' => 'Direct Messages', 'url' => route('messages.index'), 'icon' => 'fa-comments'],
                ],
                'chips' => ['How do I claim an item?', 'How does matching work?', 'Download System Guide'],
            ];
        }

        // 7. Security Desk & Physical Office Location
        if (Str::contains($q, ['office', 'security', 'location', 'desk', 'phone', 'contact', 'hours', 'help desk', 'where'])) {
            return [
                'reply' => "The SJUT Campus Security Office is located at:\n\n"
                    . "📍 **Administration Block, Ground Floor (Near East Gate)**\n"
                    . "🕒 **Hours**: Monday - Friday: 08:00 - 18:00 | Saturday: 09:00 - 13:00\n"
                    . "📞 **Security Helpline**: +255 26 239 0044\n"
                    . "✉️ **Email**: security@sjut.ac.tz\n\n"
                    . "Visit the desk for physical item surrender or claiming with your QR Release Pass.",
                'actions' => [
                    ['label' => 'Download User Guide (PDF)', 'url' => route('guide.download'), 'icon' => 'fa-file-arrow-down', 'primary' => true],
                ],
                'chips' => ['How do I claim an item?', 'How to report found item', 'Browse Found Items'],
            ];
        }

        // 8. Download Guide
        if (Str::contains($q, ['guide', 'manual', 'download', 'pdf', 'document', 'instructions', 'how the system works'])) {
            return [
                'reply' => "You can download the official **SJUT Lost & Found System User Guide** in PDF format.\n\n"
                    . "The document covers complete step-by-step instructions, reporting procedures, security verification, and campus contacts.",
                'actions' => [
                    ['label' => 'Download Guide (PDF)', 'url' => route('guide.download'), 'icon' => 'fa-file-arrow-down', 'primary' => true],
                    ['label' => 'Read Guide Online', 'url' => route('guide.index'), 'icon' => 'fa-book-open'],
                ],
                'chips' => ['How to report lost item', 'How to report found item', 'Where is Security Desk?'],
            ];
        }

        // 9. Greetings & Polite Salutations
        if (Str::contains($q, ['hi', 'hello', 'hey', 'jambo', 'habari', 'good morning', 'good afternoon', 'good evening'])) {
            return [
                'reply' => "Hello! Welcome to the St. John's University of Tanzania Lost & Found Assistant. How may I assist you today?\n\n"
                    . "You can ask me anything about reporting items, searching the directory, ownership verification, or downloading the user guide.",
                'actions' => [
                    ['label' => 'I Lost Something', 'url' => route('lost-items.create'), 'icon' => 'fa-circle-exclamation'],
                    ['label' => 'I Found Something', 'url' => route('found-items.create'), 'icon' => 'fa-hand-holding-hand'],
                    ['label' => 'Download Guide', 'url' => route('guide.download'), 'icon' => 'fa-file-arrow-down'],
                ],
                'chips' => ['How to report a lost item', 'How to report a found item', 'How does claiming work?', 'Where is the Security Desk?'],
            ];
        }

        // 10. Default fallback
        return [
            'reply' => "I'm here to help you navigate the SJUT Lost & Found system! Here are some common topics you can ask me about:\n\n"
                . "• How to report lost or found property\n"
                . "• How to search the registry and filter items\n"
                . "• How to submit an ownership claim and receive a QR Release Pass\n"
                . "• Campus Security Office location and operating hours\n"
                . "• Download the complete operational guide\n\n"
                . "Feel free to pick one of the quick options below or rephrase your question.",
            'actions' => $this->getDefaultActions(),
            'chips' => $this->getDefaultChips(),
        ];
    }

    private function getDefaultActions(): array
    {
        return [
            ['label' => 'Report Lost Item', 'url' => route('lost-items.create'), 'icon' => 'fa-circle-exclamation'],
            ['label' => 'Report Found Item', 'url' => route('found-items.create'), 'icon' => 'fa-hand-holding-hand'],
            ['label' => 'Download User Guide', 'url' => route('guide.download'), 'icon' => 'fa-file-arrow-down'],
        ];
    }

    private function getDefaultChips(): array
    {
        return [
            'How do I report a lost item?',
            'How do I report a found item?',
            'How does the claim process work?',
            'Where is the Security Desk?',
            'Download System Guide',
        ];
    }
}
