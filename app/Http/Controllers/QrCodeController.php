<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\QrCode;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    // Public Scan Route: /v/{token}
    public function publicScan($token)
    {
        $token = trim($token);
        $qr = QrCode::where('qr_token', $token)
            ->orWhere('reference_number', $token)
            ->first();

        if (!$qr) {
            return redirect()->route('landing')
                ->with('error', 'QR Code Not Recognized. Item record could not be found.');
        }

        // Fetch associated item record
        $item = $qr->item_record;

        if (!$item) {
            return redirect()->route('landing')
                ->with('error', 'Associated item record is no longer available.');
        }

        // Increment scan counters silently
        $qr->increment('scans_count');
        $qr->update(['last_scanned_at' => now()]);

        AuditService::log('qr_scanned', 'QrCode', $qr->id, null, [
            'reference' => $qr->reference_number,
            'ip' => request()->ip(),
        ]);

        return view('qr.public_scan', compact('qr', 'item'));
    }

    // Staff/Admin Action: Generate QR Code for Found or Lost Item
    public function generate(Request $request)
    {
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Only Staff or Administrators can generate property QR tags.');
        }

        $request->validate([
            'item_type' => 'required|in:found_item,lost_item',
            'item_id' => 'required|integer',
        ]);

        if ($request->item_type === 'found_item') {
            $item = FoundItem::findOrFail($request->item_id);
            $prefix = 'SJUT-FD-';
        } else {
            $item = LostItem::findOrFail($request->item_id);
            $prefix = 'SJUT-LT-';
        }

        $refNumber = $prefix . date('Y') . '-' . sprintf('%06d', $item->id);
        $qrToken = 'QRTAG-' . Str::upper(Str::random(12));

        $qr = QrCode::updateOrCreate(
            [
                'item_type' => $request->item_type,
                'item_id' => $request->item_id,
            ],
            [
                'reference_number' => $refNumber,
                'qr_token' => $qrToken,
                'status' => 'active',
                'generated_by' => Auth::id(),
                'deactivated_at' => null,
            ]
        );

        AuditService::log('qr_generated', 'QrCode', $qr->id, null, [
            'reference' => $refNumber,
            'item_name' => $item->item_name,
        ]);

        return back()->with('success', 'QR Code Property Tag generated successfully! Reference: ' . $refNumber);
    }

    // Staff/Admin Action: View & Print Physical Tag
    public function printTag($token)
    {
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Only Staff or Administrators can view printable property tags.');
        }

        $qr = QrCode::where('qr_token', $token)
            ->orWhere('reference_number', $token)
            ->firstOrFail();

        $item = $qr->item_record;

        return view('qr.print_tag', compact('qr', 'item'));
    }

    // Staff/Admin Action: Regenerate QR Token (Invalidates old QR code)
    public function regenerate(Request $request, $id)
    {
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Only Staff or Administrators can regenerate QR codes.');
        }

        $qr = QrCode::findOrFail($id);
        $oldToken = $qr->qr_token;
        $newToken = 'QRTAG-' . Str::upper(Str::random(12));

        $qr->update([
            'qr_token' => $newToken,
            'status' => 'active',
            'deactivated_at' => null,
        ]);

        AuditService::log('qr_regenerated', 'QrCode', $qr->id, ['old_token' => $oldToken], ['new_token' => $newToken]);

        return back()->with('success', 'QR Code regenerated successfully! Previous QR pass invalidated.');
    }

    // Staff/Admin Action: Deactivate QR Code
    public function deactivate(Request $request, $id)
    {
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Only Staff or Administrators can deactivate QR codes.');
        }

        $qr = QrCode::findOrFail($id);
        $qr->update([
            'status' => 'inactive',
            'deactivated_at' => now(),
        ]);

        AuditService::log('qr_deactivated', 'QrCode', $qr->id, null, ['status' => 'inactive']);

        return back()->with('success', 'QR Code tag deactivated successfully.');
    }
}
