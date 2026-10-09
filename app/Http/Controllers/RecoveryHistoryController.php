<?php

namespace App\Http\Controllers;

use App\Models\RecoveryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecoveryHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $histories = RecoveryHistory::with(['owner', 'finder', 'claim'])->latest()->paginate(15);
        } else {
            $histories = RecoveryHistory::with(['owner', 'finder', 'claim'])
                ->where('owner_id', $user->id)
                ->orWhere('finder_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('recovery.index', compact('histories'));
    }
}
