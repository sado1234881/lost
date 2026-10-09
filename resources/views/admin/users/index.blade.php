@extends('layouts.app')

@section('title', 'Registered User Administration')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">User Management</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
            Registered User Administration
        </h1>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Audit accounts, manage security & administrative roles, and inspect user activity metrics.</p>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Users -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Accounts</span>
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalUsers }}</span>
            <span class="text-[11px] text-emerald-600 font-bold block flex items-center gap-1">
                <i class="fa-solid fa-circle text-[6px] animate-pulse"></i> Registered Users
            </span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-950 to-indigo-900 text-white flex items-center justify-center text-xl shadow-md">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <!-- Administrators -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Administrators</span>
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $adminCount }}</span>
            <span class="text-[11px] text-purple-600 dark:text-purple-400 font-bold block">Full Audit Rights</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-900 to-indigo-900 text-white flex items-center justify-center text-xl shadow-md">
            <i class="fa-solid fa-user-shield"></i>
        </div>
    </div>

    <!-- Security Staff -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Security & Staff</span>
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $staffCount }}</span>
            <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold block">Custody & Verification</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-800 to-teal-900 text-white flex items-center justify-center text-xl shadow-md">
            <i class="fa-solid fa-id-badge"></i>
        </div>
    </div>

    <!-- Students -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Students & Users</span>
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $studentCount }}</span>
            <span class="text-[11px] text-amber-600 dark:text-amber-400 font-bold block">Campus Reporters</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-600 to-orange-700 text-white flex items-center justify-center text-xl shadow-md">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-4 mb-6 shadow-sm">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <!-- Search Field -->
        <div class="relative flex-1 w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, registration number or phone..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-900">
        </div>

        <!-- Filter Buttons -->
        <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
            <a href="{{ route('admin.users.index', ['search' => $search, 'role' => 'all']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ !$roleFilter || $roleFilter === 'all' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                All Roles
            </a>
            <a href="{{ route('admin.users.index', ['search' => $search, 'role' => 'student']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ $roleFilter === 'student' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                Students
            </a>
            <a href="{{ route('admin.users.index', ['search' => $search, 'role' => 'staff']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ $roleFilter === 'staff' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                Staff
            </a>
            <a href="{{ route('admin.users.index', ['search' => $search, 'role' => 'admin']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap {{ $roleFilter === 'admin' ? 'bg-blue-900 text-white shadow-sm' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">
                Admins
            </a>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-filter text-[10px]"></i> Apply
            </button>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-zinc-800/60 border-b border-slate-200 dark:border-zinc-800 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-4">User Details</th>
                    <th class="py-4 px-4">Registration No.</th>
                    <th class="py-4 px-4">Role Badge</th>
                    <th class="py-4 px-4 text-center">Activity Metrics</th>
                    <th class="py-4 px-4">Joined Date</th>
                    <th class="py-4 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-zinc-800/40 transition-colors">
                        <!-- User Details -->
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-950 to-indigo-900 text-white font-extrabold text-xs flex items-center justify-center uppercase shrink-0 shadow-sm">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <span class="font-extrabold text-slate-900 dark:text-white block">{{ $user->name }}</span>
                                    <span class="text-slate-400 dark:text-zinc-400 block text-[11px]">{{ $user->email }}</span>
                                    @if($user->phone_number)
                                        <span class="text-[10px] text-slate-400 block"><i class="fa-solid fa-phone text-[9px] mr-1"></i>{{ $user->phone_number }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Reg No -->
                        <td class="py-4 px-4 font-mono font-bold text-slate-700 dark:text-zinc-300">
                            {{ $user->registration_number ?? 'N/A' }}
                        </td>

                        <!-- Role Badge -->
                        <td class="py-4 px-4">
                            @if($user->isAdmin())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                    <i class="fa-solid fa-user-shield text-[10px]"></i> Administrator
                                </span>
                            @elseif($user->isStaff())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <i class="fa-solid fa-id-badge text-[10px]"></i> Security Staff
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    <i class="fa-solid fa-graduation-cap text-[10px]"></i> Student / User
                                </span>
                            @endif
                        </td>

                        <!-- Activity Overview -->
                        <td class="py-4 px-4 text-center">
                            <div class="inline-flex items-center gap-2 text-[11px]">
                                <span title="Lost Property Reports" class="px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 font-extrabold border border-amber-200 dark:border-amber-900">
                                    <i class="fa-solid fa-circle-exclamation text-[9px] mr-1"></i>{{ $user->lost_items_count }} Lost
                                </span>
                                <span title="Found Property Reports" class="px-2.5 py-1 rounded-lg bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 font-extrabold border border-purple-200 dark:border-purple-900">
                                    <i class="fa-solid fa-boxes-stacked text-[9px] mr-1"></i>{{ $user->found_items_count }} Found
                                </span>
                                <span title="Ownership Claims" class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 font-extrabold border border-emerald-200 dark:border-emerald-900">
                                    <i class="fa-solid fa-file-signature text-[9px] mr-1"></i>{{ $user->claims_count }} Claims
                                </span>
                            </div>
                        </td>

                        <!-- Joined Date -->
                        <td class="py-4 px-4 text-slate-400 dark:text-zinc-500 font-medium">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($user->id !== Auth::id())
                                    <a href="{{ route('messages.index', $user->id) }}" class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 hover:bg-blue-900 hover:text-white flex items-center justify-center transition-colors shadow-xs" title="Send Direct Message">
                                        <i class="fa-solid fa-comments text-xs"></i>
                                    </a>
                                @endif

                                <button onclick="openEditModal({{ json_encode($user) }})" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 hover:bg-slate-200 dark:hover:bg-zinc-700 flex items-center justify-center transition-colors shadow-xs" title="Edit User Account">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>

                                @if($user->id !== Auth::id())
                                    <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors shadow-xs" title="Delete User Account">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 dark:text-zinc-500">
                            <i class="fa-solid fa-user-slash text-3xl mb-2 block"></i>
                            <p class="font-bold text-xs">No registered users match your criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- EDIT USER MODAL -->
<div id="edit-user-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl w-full max-w-lg shadow-2xl p-6 relative animate-fade-in-up">
        <button onclick="closeEditModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-950 to-indigo-900 text-white flex items-center justify-center text-lg shadow-md font-bold">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Edit User Account</h3>
                <p class="text-xs text-slate-400">Update account credentials, assign roles or reset password.</p>
            </div>
        </div>

        <form id="edit-user-form" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-zinc-300 mb-1">Full Name</label>
                    <input type="text" id="edit-name" name="name" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-zinc-300 mb-1">Email Address</label>
                        <input type="email" id="edit-email" name="email" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-zinc-300 mb-1">Registration Number</label>
                        <input type="text" id="edit-reg" name="registration_number" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-zinc-300 mb-1">Phone Number</label>
                        <input type="text" id="edit-phone" name="phone_number" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-zinc-300 mb-1">Account Role</label>
                        <select id="edit-role" name="role" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                            <option value="student">Student / User</option>
                            <option value="staff">Security Staff</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-zinc-300 mb-1">Reset Password <span class="text-slate-400 font-normal">(Leave blank to keep unchanged)</span></label>
                    <input type="password" name="password" placeholder="New password min 8 characters" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-900 focus:outline-none">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 font-bold hover:bg-slate-200 text-xs cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-950 to-indigo-900 hover:from-blue-900 hover:to-indigo-800 text-white font-extrabold text-xs shadow-md cursor-pointer">Save Account Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE USER MODAL -->
<div id="delete-user-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl w-full max-w-md shadow-2xl p-6 relative animate-fade-in-up">
        <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex items-center justify-center text-xl mb-4 mx-auto">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white text-center mb-1">Confirm Account Deletion</h3>
        <p class="text-xs text-slate-500 dark:text-zinc-400 text-center mb-6">Are you sure you want to delete <span id="delete-user-name" class="font-bold text-slate-900 dark:text-white"></span>? This action cannot be reversed.</p>

        <form id="delete-user-form" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 font-bold text-xs hover:bg-slate-200 w-1/2 cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-md w-1/2 cursor-pointer">Delete Account</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(user) {
        document.getElementById('edit-user-form').action = '/admin/users/' + user.id;
        document.getElementById('edit-name').value = user.name || '';
        document.getElementById('edit-email').value = user.email || '';
        document.getElementById('edit-reg').value = user.registration_number || '';
        document.getElementById('edit-phone').value = user.phone_number || '';
        document.getElementById('edit-role').value = user.role === 'user' ? 'student' : user.role;
        document.getElementById('edit-user-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-user-modal').classList.add('hidden');
    }

    function openDeleteModal(userId, userName) {
        document.getElementById('delete-user-form').action = '/admin/users/' + userId;
        document.getElementById('delete-user-name').innerText = userName;
        document.getElementById('delete-user-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-user-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
