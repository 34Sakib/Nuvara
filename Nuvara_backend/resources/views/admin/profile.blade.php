@extends('layouts.admin')

@section('title', 'Admin - Profile')
@section('header_title', 'Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 text-left">
    <!-- Profile Card Container -->
    <div class="glass-card rounded-2xl p-6 md:p-8 shadow-md relative overflow-hidden transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-8 border-b border-border pb-3">
            <div>
                <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                    Administrator Profile Settings
                </h3>
                <p class="text-[10px] text-gray-500 font-semibold mt-0.5">Manage your credential credentials and contact details</p>
            </div>
            <span class="text-xl bg-brass/10 w-9 h-9 rounded-lg flex items-center justify-center select-none">⚙️</span>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Display Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        required 
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200"
                    >
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Phone Number</label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone', $user->phone) }}"
                        placeholder="+1 (555) 000-0000"
                        class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200"
                    >
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    required 
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200"
                >
            </div>

            <!-- Password Change Divider -->
            <div class="border-t border-border pt-6">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
                    Change Password (Optional)
                </h4>
                <p class="text-[10px] text-gray-500 font-semibold mb-6">Leave blank if you do not want to change your current login password</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- New Password -->
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">New Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200"
                        >
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Confirm New Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200"
                        >
                    </div>
                </div>
            </div>

            <div class="pt-4 text-right">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
