@extends('layouts.admin')

@section('title', 'Admin - Contact Page Settings')
@section('header_title', 'Contact Page Management')

@section('content')
<div class="space-y-8 animate-fade-in text-left">
    
    <div class="glass-card rounded-2xl p-6 md:p-8 shadow-xl border border-brass/20">
        <h2 class="text-xl font-serif text-brass uppercase tracking-wide mb-6 border-b border-border pb-3">
            Dynamic Contact Us Page Configuration
        </h2>

        <form action="{{ route('admin.pages.contact.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- 1. Hero Section -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">1. Hero Banner Header</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Badge Text (EN)</label>
                        <input type="text" name="hero_badge_en" value="{{ $content['hero_badge']['en'] ?? '24/7 Multilingual Support Hub' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Badge Text (BN)</label>
                        <input type="text" name="hero_badge_bn" value="{{ $content['hero_badge']['bn'] ?? '২৪/৭ বহুমুখী সাহায্য কেন্দ্র' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Main Title (EN)</label>
                        <input type="text" name="hero_title_en" value="{{ $content['hero_title']['en'] ?? 'Get In Touch With Us' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Main Title (BN)</label>
                        <input type="text" name="hero_title_bn" value="{{ $content['hero_title']['bn'] ?? 'আমাদের সাথে যোগাযোগ করুন' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Subtitle Bio (EN)</label>
                        <textarea name="hero_subtitle_en" rows="3" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">{{ $content['hero_subtitle']['en'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Subtitle Bio (BN)</label>
                        <textarea name="hero_subtitle_bn" rows="3" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">{{ $content['hero_subtitle']['bn'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. Contact Information Cards -->
            <div class="space-y-4 pt-4 border-t border-border">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">2. Contact Info Cards (Email, Phone, Headquarters)</h3>
                
                @php
                    $cards = $content['cards'] ?? [];
                    $emailCard = $cards[0] ?? [];
                    $phoneCard = $cards[1] ?? [];
                    $addressCard = $cards[2] ?? [];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Email Card -->
                    <div class="p-4 rounded-xl border border-border bg-black/20 space-y-3">
                        <span class="text-xs font-bold text-brass uppercase">📧 Support Email Card</span>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Email Address</label>
                            <input type="email" name="email_val" value="{{ $emailCard['value'] ?? 'support@nuvara.com' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Subtext (EN)</label>
                            <input type="text" name="email_sub_en" value="{{ $emailCard['sub']['en'] ?? 'Response within 2 hours' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Subtext (BN)</label>
                            <input type="text" name="email_sub_bn" value="{{ $emailCard['sub']['bn'] ?? '২ ঘণ্টার মধ্যে উত্তর' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="p-4 rounded-xl border border-border bg-black/20 space-y-3">
                        <span class="text-xs font-bold text-brass uppercase">📞 Phone Hotline Card</span>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Hotline Number</label>
                            <input type="text" name="phone_val" value="{{ $phoneCard['value'] ?? '+1 (800) 555-NUVARA' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Subtext (EN)</label>
                            <input type="text" name="phone_sub_en" value="{{ $phoneCard['sub']['en'] ?? 'Mon - Sun, 24/7 Hotline' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Subtext (BN)</label>
                            <input type="text" name="phone_sub_bn" value="{{ $phoneCard['sub']['bn'] ?? 'সোম - রবি, ২৪/৭ হটলাইন' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                    </div>

                    <!-- Address Card -->
                    <div class="p-4 rounded-xl border border-border bg-black/20 space-y-3">
                        <span class="text-xs font-bold text-brass uppercase">📍 Office Headquarters Card</span>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Office Location</label>
                            <input type="text" name="address_val" value="{{ $addressCard['value'] ?? 'San Francisco, CA' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Subtext (EN)</label>
                            <input type="text" name="address_sub_en" value="{{ $addressCard['sub']['en'] ?? '100 Embassy Row, Suite 400' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                        <div>
                            <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Subtext (BN)</label>
                            <input type="text" name="address_sub_bn" value="{{ $addressCard['sub']['bn'] ?? '১০০ এম্বাসি রো, স্যুট ৪০০' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="px-8 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-xl transition-all shadow-lg active:scale-95 duration-150">
                    Save Contact Settings
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
