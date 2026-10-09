@extends('layouts.app')

@section('title', 'Security Property Intake')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <a href="{{ route('staff.index') }}" class="hover:underline">Staff Portal</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Security Intake Log</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Security Property Intake Log</h1>
    </div>

    <a href="{{ route('found-items.create') }}" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> New Property Intake Form
    </a>
</div>

<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 dark:bg-zinc-800 text-slate-500 uppercase font-bold text-[10px]">
                <tr>
                    <th class="p-3">Ref ID</th>
                    <th class="p-3">Item Name</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Storage Cabinet / Cabinet</th>
                    <th class="p-3">Date Found</th>
                    <th class="p-3">Approval Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($recentIntakes as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/40">
                        <td class="p-3 font-mono font-bold text-slate-700 dark:text-zinc-300">#LF-F{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $item->item_name }}</td>
                        <td class="p-3 text-slate-600 dark:text-zinc-400">{{ $item->category->category_name ?? 'General' }}</td>
                        <td class="p-3 font-semibold text-slate-700 dark:text-zinc-300">{{ $item->storage_location }}</td>
                        <td class="p-3 text-slate-500">{{ $item->date_found->format('M d, Y') }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $item->approval_status }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <a href="{{ route('found-items.show', $item->id) }}" class="px-3 py-1 rounded bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-200 font-bold hover:bg-slate-300">
                                Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-400 text-xs">No property intakes logged yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $recentIntakes->links() }}</div>
</div>
@endsection
