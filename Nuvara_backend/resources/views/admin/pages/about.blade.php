@extends('layouts.admin')

@section('title', 'Admin - About Us Settings')
@section('header_title', 'About Us Page Management')

@section('content')
<div class="space-y-8 animate-fade-in text-left">
    
    <div class="glass-card rounded-2xl p-6 md:p-8 shadow-xl border border-brass/20">
        <h2 class="text-xl font-serif text-brass uppercase tracking-wide mb-6 border-b border-border pb-3">
            Dynamic About Us Page Configuration
        </h2>

        <form action="{{ route('admin.pages.about.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- 1. Hero Section -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">1. Hero Header Banner</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Badge Text (EN)</label>
                        <input type="text" name="hero_badge_en" value="{{ $content['hero_badge']['en'] ?? 'Established 2026 • Global Commerce' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Badge Text (BN)</label>
                        <input type="text" name="hero_badge_bn" value="{{ $content['hero_badge']['bn'] ?? 'প্রতিষ্ঠিত ২০২৬ • গ্লোবাল কমার্স' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Main Headline (EN)</label>
                        <input type="text" name="hero_title_en" value="{{ $content['hero_title']['en'] ?? 'Redefining Localized E-Commerce Worldwide' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Main Headline (BN)</label>
                        <input type="text" name="hero_title_bn" value="{{ $content['hero_title']['bn'] ?? 'বিশ্বজুড়ে রিডিফাইনিং লোকালাইজড ই-কমার্স' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
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

            <!-- 2. Statistics Bar -->
            <div class="space-y-4 pt-4 border-t border-border">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">2. Statistics Highlights (4 Items)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @for($i = 0; $i < 4; $i++)
                        @php 
                            $stat = $content['stats'][$i] ?? []; 
                            $num = $i + 1;
                        @endphp
                        <div class="p-4 rounded-xl border border-border bg-black/20 space-y-3">
                            <span class="text-xs font-bold text-brass uppercase">Stat #{{ $num }}</span>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Value</label>
                                <input type="text" name="stat{{ $num }}_val" value="{{ $stat['value'] ?? '' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Label (EN)</label>
                                <input type="text" name="stat{{ $num }}_label_en" value="{{ $stat['label']['en'] ?? '' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Label (BN)</label>
                                <input type="text" name="stat{{ $num }}_label_bn" value="{{ $stat['label']['bn'] ?? '' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- 3. Brand Story Section -->
            <div class="space-y-4 pt-4 border-t border-border">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">3. Brand Story & Mission Statement</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Story Headline (EN)</label>
                        <input type="text" name="story_title_en" value="{{ $content['story_title']['en'] ?? '' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Story Headline (BN)</label>
                        <input type="text" name="story_title_bn" value="{{ $content['story_title']['bn'] ?? '' }}" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Story Paragraph (EN)</label>
                        <textarea name="story_body_en" rows="4" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">{{ $content['story_body']['en'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Story Paragraph (BN)</label>
                        <textarea name="story_body_bn" rows="4" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">{{ $content['story_body']['bn'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 4. Team Members Section -->
            <div class="space-y-4 pt-4 border-t border-border">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">4. Leadership Team Members (3 Members)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @for($i = 0; $i < 3; $i++)
                        @php 
                            $member = $content['team'][$i] ?? []; 
                            $num = $i + 1;
                        @endphp
                        <div class="p-4 rounded-xl border border-border bg-black/20 space-y-3">
                            <span class="text-xs font-bold text-brass uppercase">Team Member #{{ $num }}</span>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Full Name</label>
                                <input type="text" name="team{{ $num }}_name" value="{{ $member['name'] ?? '' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Role (EN)</label>
                                <input type="text" name="team{{ $num }}_role_en" value="{{ $member['role']['en'] ?? '' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Role (BN)</label>
                                <input type="text" name="team{{ $num }}_role_bn" value="{{ $member['role']['bn'] ?? '' }}" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Avatar Image URL</label>
                                <input type="url" name="team{{ $num }}_image" value="{{ $member['image'] ?? '' }}" class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Bio (EN)</label>
                                <textarea name="team{{ $num }}_bio_en" rows="2" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">{{ $member['bio']['en'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[9px] text-gray-400 font-bold uppercase mb-1">Bio (BN)</label>
                                <textarea name="team{{ $num }}_bio_bn" rows="2" required class="w-full px-3 py-1.5 rounded border border-border bg-black/40 text-xs text-gray-200">{{ $member['bio']['bn'] ?? '' }}</textarea>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="px-8 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-xl transition-all shadow-lg active:scale-95 duration-150">
                    Save About Us Settings
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
