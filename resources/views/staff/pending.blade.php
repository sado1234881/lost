@extends('layouts.app')

@section('title', 'Pending Review Queue')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <a href="{{ route('staff.index') }}" class="hover:underline">Staff Portal</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Approval Queue</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Reports Awaiting Security Approval</h1>
    </div>
</div>

<div class="space-y-8">

    <!-- Pending Lost Items Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-amber-500"></i> Pending Lost Property Reports ({{ $pendingLost->total() }})
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-slate-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Report ID</th>
                        <th class="p-3">Item Name</th>
                        <th class="p-3">Reporter</th>
                        <th class="p-3">Date Lost</th>
                        <th class="p-3">Location</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($pendingLost as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/40">
                            <td class="p-3 font-mono font-bold text-slate-700 dark:text-zinc-300">#LF-L{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $item->item_name }}</td>
                            <td class="p-3 text-slate-600 dark:text-zinc-400">{{ $item->user->name ?? 'N/A' }} ({{ $item->user->registration_number ?? 'N/A' }})</td>
                            <td class="p-3 text-slate-500">{{ $item->date_lost->format('M d, Y') }}</td>
                            <td class="p-3 text-slate-500">{{ $item->location_lost }}</td>
                            <td class="p-3 text-right space-x-2">
                                <a href="{{ route('lost-items.show', $item->id) }}" class="px-2.5 py-1 rounded bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-200 font-bold hover:bg-slate-300">
                                    Review
                                </a>
                                <form action="{{ route('lost-items.approve', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-bold">
                                        Approve
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400 text-xs">No pending lost property reports.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $pendingLost->links() }}</div>
    </div>

    <!-- Pending Found Items Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-hand-holding-hand text-emerald-500"></i> Pending Found Property Entries ({{ $pendingFound->total() }})
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-slate-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Report ID</th>
                        <th class="p-3">Item Name</th>
                        <th class="p-3">Finder / Reporter</th>
                        <th class="p-3">Storage Location</th>
                        <th class="p-3">Date Found</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($pendingFound as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/40">
                            <td class="p-3 font-mono font-bold text-slate-700 dark:text-zinc-300">#LF-F{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $item->item_name }}</td>
                            <td class="p-3 text-slate-600 dark:text-zinc-400">{{ $item->user->name ?? 'N/A' }}</td>
                            <td class="p-3 text-slate-500">{{ $item->storage_location }}</td>
                            <td class="p-3 text-slate-500">{{ $item->date_found->format('M d, Y') }}</td>
                            <td class="p-3 text-right space-x-2">
                                <a href="{{ route('found-items.show', $item->id) }}" class="px-2.5 py-1 rounded bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-200 font-bold hover:bg-slate-300">
                                    Review
                                </a>
                                <form action="{{ route('found-items.approve', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-bold">
                                        Approve
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400 text-xs">No pending found property submissions.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $pendingFound->links() }}</div>
    </div>

</div>
@endsection
