@extends('layouts.admin')

@section('title', 'Admin - Users')
@section('header_title', 'Users')

@section('content')
<div class="space-y-8">
    <!-- Add User Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Create User / Administrator Account
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Registry: {{ count($users) }} Users</span>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Full Name</label>
                <input type="text" name="name" required placeholder="John Doe" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="john.doe@example.com" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Phone</label>
                <input type="text" name="phone" placeholder="+1 (555) 012-3456" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Role Level</label>
                <select name="role" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="user">Customer / User</option>
                    <option value="admin">System Administrator</option>
                </select>
            </div>

            <div class="md:col-span-5 pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-200">
                    Add User Account
                </button>
            </div>
        </form>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-20">ID</th>
                        <th class="p-5">Customer Profile</th>
                        <th class="p-5">Email Address</th>
                        <th class="p-5">Phone Number</th>
                        <th class="p-5 text-center">Locale Preference</th>
                        <th class="p-5 text-center">Theme Preference</th>
                        <th class="p-5">Created At</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @foreach($users as $u)
                        <tr class="hover:bg-white/5 transition-colors duration-250">
                            <td class="p-5 text-gray-500 font-mono text-xs">
                                #{{ $u->id }}
                            </td>
                            <td class="p-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 bg-brass/10 border border-brass/15 text-brass font-bold rounded-full flex items-center justify-center uppercase text-sm select-none">
                                        {{ substr($u->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#F5EFE4] text-sm flex items-center space-x-2">
                                            <span>{{ $u->name }}</span>
                                            @if($u->role === 'admin')
                                                <span class="px-2 py-0.5 text-[8px] font-black uppercase bg-brass text-black rounded select-none">Admin</span>
                                            @else
                                                <span class="px-2 py-0.5 text-[8px] font-bold uppercase bg-zinc-800 border border-zinc-700 text-gray-400 rounded select-none">Customer</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 text-gray-400 text-xs font-semibold">
                                {{ $u->email }}
                            </td>
                            <td class="p-5 text-gray-400 font-mono text-xs">
                                {{ $u->phone ?: 'Not provided' }}
                            </td>
                            <td class="p-5 text-center text-xs font-black uppercase text-brass">
                                {{ $u->locale_preference ?: 'en' }}
                            </td>
                            <td class="p-5 text-center">
                                <span class="px-2.5 py-1 text-[9px] font-bold uppercase rounded-md tracking-wide select-none {{ $u->theme_preference === 'dark' ? 'bg-surface border border-border text-gray-400' : 'bg-gray-200 border border-gray-300 text-gray-800' }}">
                                    {{ $u->theme_preference ?: 'light' }}
                                </span>
                            </td>
                            <td class="p-5 text-gray-400 text-xs font-semibold">
                                {{ $u->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-5 text-right">
                                <button onclick='openEditModal({!! json_encode($u) !!})' class="px-3 py-1.5 border border-brass/35 text-brass hover:bg-brass/10 rounded-lg text-xs font-bold transition-all duration-150">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit User Modal Backdrop Overlay -->
<div id="edit-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden animate-fade-in">
    <div class="glass-card w-full max-w-xl rounded-2xl p-6 md:p-8 relative shadow-2xl text-left border border-brass/25">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white text-lg">✕</button>
        <h3 class="text-sm font-bold text-brass uppercase tracking-wider mb-6 border-b border-border pb-3">
            Edit User Profile Configuration
        </h3>
        
        <form id="edit-form" action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Full Name</label>
                <input type="text" id="edit-name" name="name" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" id="edit-email" name="email" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Phone</label>
                <input type="text" id="edit-phone" name="phone" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Role Level</label>
                <select id="edit-role" name="role" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="user">Customer / User</option>
                    <option value="admin">System Administrator</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Password (Leave blank to keep unchanged)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div class="md:col-span-2 pt-2 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 border border-border hover:bg-white/5 text-gray-300 font-bold uppercase text-xs tracking-wider rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(usr) {
        document.getElementById('edit-form').action = '/admin/users/' + usr.id + '/update';
        document.getElementById('edit-name').value = usr.name || '';
        document.getElementById('edit-email').value = usr.email || '';
        document.getElementById('edit-phone').value = usr.phone || '';
        document.getElementById('edit-role').value = usr.role || 'user';
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
</script>
@endsection
