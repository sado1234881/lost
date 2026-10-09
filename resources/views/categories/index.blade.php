@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Categories</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Item Categories Management</h1>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Categories List (2 cols) -->
    <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
        <h3 class="font-bold text-slate-800 dark:text-white text-base mb-4">Existing Property Categories</h3>
        
        <div class="space-y-3">
            @foreach($categories as $cat)
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-zinc-700 text-blue-900 dark:text-blue-300 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid {{ $cat->icon }}"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $cat->category_name }}</h4>
                            <p class="text-xs text-slate-400">{{ $cat->description }}</p>
                            <span class="text-[10px] text-slate-400 mt-1 block">
                                Lost: <strong>{{ $cat->lost_items_count }}</strong> | Found: <strong>{{ $cat->found_items_count }}</strong>
                            </span>
                        </div>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-50 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-xs p-2 rounded-lg font-semibold transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create Category Form (1 col) -->
    @if(Auth::user()->isAdmin())
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white text-base mb-4">Add New Category</h3>
            
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Category Name *</label>
                    <input type="text" name="category_name" required placeholder="e.g. Smart Watches"
                        class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Font Awesome Icon Class *</label>
                    <input type="text" name="icon" required placeholder="e.g. fa-stopwatch" value="fa-box"
                        class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <span class="text-[10px] text-slate-400 block mt-1">Example: fa-laptop, fa-mobile-screen-button, fa-key</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Category coverage description..."
                        class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i> Save Category
                </button>
            </form>
        </div>
    @endif

</div>
@endsection
