<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nuvara Admin')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;0,9..144,800;1,9..144,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        green: {
                            DEFAULT: '#1F3A2E',
                            soft: '#2D5241',
                            light: '#EAF0EC'
                        },
                        brass: {
                            DEFAULT: '#C5A880',
                            light: '#F4EFE8',
                            dark: '#A6875E'
                        },
                        surface: '#0d0d0d',
                        border: '#1a1a1a'
                    },
                    fontFamily: {
                        serif: ['Fraunces', 'serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .dark body {
            background-color: #060606;
            color: #f5efe4;
        }
        .light body {
            background-color: #f4f2ee;
            color: #1c1917;
        }
        .glass-card {
            transition: all 0.3s ease;
        }
        .dark .glass-card {
            background: rgba(13, 13, 13, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(197, 168, 128, 0.12);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        }
        .light .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(197, 168, 128, 0.22);
            box-shadow: 0 4px 20px rgba(197, 168, 128, 0.08);
            color: #1c1917;
        }

        /* Light mode global overrides */
        .light .text-\[\#F5EFE4\] {
            color: #1c1917 !important;
        }
        .light .text-gray-300 {
            color: #292524 !important;
        }
        .light .text-gray-400 {
            color: #57534e !important;
        }
        .light .text-gray-500 {
            color: #78716c !important;
        }
        .light .text-white {
            color: #0c0a09 !important;
        }
        .light .bg-surface {
            background-color: #fcfbf9 !important;
        }
        .light .bg-black\/40 {
            background-color: #ffffff !important;
        }
        .light .bg-black\/20 {
            background-color: #f4f2ee !important;
        }
        .light .bg-black\/50 {
            background-color: #fcfbf9 !important;
        }
        .light .border-border {
            border-color: #e7e5e4 !important;
        }
        .light .border-brass\/20 {
            border-color: rgba(197, 168, 128, 0.4) !important;
        }
        .light .divide-border > :not([hidden]) ~ :not([hidden]) {
            border-color: #e7e5e4 !important;
        }
        .light input, .light select, .light textarea {
            background-color: #ffffff !important;
            color: #1c1917 !important;
            border-color: #d6d3d1 !important;
        }
        .light input::placeholder {
            color: #a8a29e !important;
        }
        .light table th {
            color: #44403c !important;
            background-color: #f5f4f0 !important;
            border-color: #e7e5e4 !important;
        }
        .light table td {
            color: #1c1917 !important;
            border-color: #e7e5e4 !important;
        }
        .light .divide-y > :not([hidden]) ~ :not([hidden]) {
            border-color: #e7e5e4 !important;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .dark ::-webkit-scrollbar-track {
            background: #060606;
        }
        .light ::-webkit-scrollbar-track {
            background: #f4f2ee;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #1f1f1f;
            border-radius: 3px;
        }
        .light ::-webkit-scrollbar-thumb {
            background: #d6d3d1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #c5a880;
        }
    </style>
    <style>
      :root{--admin-green:#173d31;--admin-brass:#c5a880;--admin-ivory:#f7f5f0} body{letter-spacing:-.01em;background:linear-gradient(135deg,#102d25 0%,#173d31 42%,#244e40 100%)!important}.glass-card{border-radius:18px!important;overflow:hidden;background:linear-gradient(145deg,rgba(255,255,255,.10),rgba(255,255,255,.045))!important;border:1px solid rgba(255,255,255,.14)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(4,20,15,.22)!important}.glass-card h1,.glass-card h2,.glass-card h3{font-family:Fraunces,serif;letter-spacing:-.02em;color:#fff}main{scroll-behavior:smooth}main>div{animation:adminRise .35s ease-out both}input,select,textarea{min-height:42px;border-radius:10px!important;transition:border-color .2s,box-shadow .2s;background:rgba(8,25,20,.38)!important;color:#fff!important;border-color:rgba(255,255,255,.16)!important}input:focus,select:focus,textarea:focus{box-shadow:0 0 0 3px rgba(197,168,128,.18)!important;border-color:#c5a880!important}.glass-card button,.glass-card a{transition:transform .2s,box-shadow .2s,background .2s}.glass-card button:hover,.glass-card a:hover{transform:translateY(-1px)}table thead th{font-size:10px!important;letter-spacing:.12em!important;padding-top:15px!important;padding-bottom:15px!important;background:rgba(197,168,128,.10)!important;color:#eadcc5!important}table tbody td{vertical-align:middle;color:#e8eee9}.dark table tbody tr:hover{background:rgba(197,168,128,.09)}aside{background:rgba(7,27,21,.72)!important;border-color:rgba(255,255,255,.12)!important;backdrop-filter:blur(22px)}aside nav a{min-height:43px;border-radius:11px!important;letter-spacing:.08em!important}aside nav a.bg-brass{box-shadow:0 8px 20px rgba(197,168,128,.25)}aside nav a span:first-child{display:grid;place-items:center;width:25px;height:25px;border-radius:8px;background:rgba(197,168,128,.16);font-size:13px;line-height:1}aside nav a.bg-brass span:first-child{background:rgba(0,0,0,.12)}@keyframes adminRise{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}@media(max-width:767px){body{display:block!important}aside{position:sticky;top:0;width:100%!important;z-index:40;border-right:0;border-bottom:1px solid rgba(255,255,255,.12)}aside>div:first-child{padding:14px 18px!important}aside nav{display:flex;gap:6px;overflow-x:auto;padding:10px!important;white-space:nowrap}aside nav a{flex:0 0 auto;padding:10px 13px!important;font-size:10px!important}aside nav a span:first-child{font-size:14px}main{padding:18px!important}table{min-width:760px}.glass-card{border-radius:14px!important}}
      .dashboard-hero{background:linear-gradient(120deg,#244f41,#173d31)!important;border:1px solid rgba(197,168,128,.32)!important}.dashboard-hero h2{font-size:clamp(1.6rem,3vw,2.5rem)!important;text-transform:none!important}.dashboard-metric{background:linear-gradient(145deg,rgba(255,255,255,.15),rgba(255,255,255,.06))!important}.dashboard-metric h3{font-size:clamp(1.5rem,2.3vw,2rem)!important}.dashboard-chart{background:rgba(7,27,21,.55)!important}
    </style>
<style>
body{background:#eee8dc!important;color:#26352e!important}.glass-card{background:#fffdf9!important;border-color:#ded5c7!important;box-shadow:0 12px 32px rgba(90,73,47,.10)!important}.glass-card h1,.glass-card h2,.glass-card h3{color:#24372e!important}input,select,textarea{background:#fff!important;color:#26352e!important;border-color:#d8cdbd!important}aside{background:#f8f4ec!important;border-color:#ded5c7!important}aside nav a:not(.bg-brass){color:#59645d!important}table thead th{background:#f2ece2!important;color:#6b5a43!important}table tbody td{color:#34443a!important}.dashboard-hero{background:linear-gradient(135deg,#fffaf1,#f0e5d3)!important}.dash-chart{background:#fffdf9!important}
</style>
<style>aside{background:#364347!important;border-color:#465257!important}aside>div:first-child{background:#364347!important;border-color:#465257!important}aside>div:first-child span:last-child{color:#fff7ea!important}aside nav a:not(.bg-brass){color:#e3e9e7!important}header{background:#364347!important;border-color:#465257!important;color:#f5f1e8!important}header .text-gray-400,header .text-stone-600,header .text-gray-500,header button,.admin-user-name,.admin-user-arrow{color:#fff!important}header svg{color:#f5f1e8!important}header .bg-brass{color:#172126!important}</style>
<style>
/* Opaque badge palettes remain readable on the ivory admin surfaces in either theme. */
html main table span[class*="bg-"] {
    --badge-bg:#edf0f3; --badge-ink:#425466; --badge-border:#d4dde5;
    background:var(--badge-bg)!important; color:var(--badge-ink)!important;
    border:1px solid var(--badge-border)!important;
    display:inline-flex; align-items:center; padding:5px 10px;
    border-radius:8px; font-size:11px!important; font-weight:700;
    line-height:1.4; letter-spacing:.025em; white-space:nowrap;
}
html main table span[class*="bg-green"],html main table span[class*="bg-emerald"] { --badge-bg:#e7f4ed; --badge-ink:#17603b; --badge-border:#bddfc9; }
html main table span[class*="bg-yellow"],html main table span[class*="bg-amber"] { --badge-bg:#fff3d6; --badge-ink:#805000; --badge-border:#ecd5a0; }
html main table span[class*="bg-red"] { --badge-bg:#fcebea; --badge-ink:#a32e35; --badge-border:#efc6c9; }
html main table span[class*="bg-indigo"],html main table span[class*="bg-blue"] { --badge-bg:#eaf0ff; --badge-ink:#304f9b; --badge-border:#c9d6f4; }
html main table span[class*="bg-purple"] { --badge-bg:#f1ebfb; --badge-ink:#654299; --badge-border:#ded0ef; }
html main table button[class*="text-red"] { background:#fff1f0!important; color:#a32e35!important; border-color:#edc3c5!important; }
html main table button[class*="text-red"]:hover { background:#fce1e0!important; }
html main table :is(a,button)[class*="text-brass"] { color:#725027!important; background:#faf2e5!important; border-color:#dbc8a9!important; }
html main table td.text-amber-400 { color:#805000!important; }
html main .glass-card label.text-brass { color:#725027!important; }
html main table :is(button,a):focus-visible { outline:3px solid #7395ce; outline-offset:3px; }
</style>
<style>
#userDropdownMenu { width:260px; max-width:calc(100vw - 32px); background:#fffdf9!important; border:1px solid #ddd2c1!important; border-radius:16px; padding:8px; box-shadow:0 18px 48px rgba(25,35,38,.24); z-index:100; }
#userDropdownMenu a,#userDropdownMenu button { color:#364347!important; border-radius:9px; padding:12px; font-size:13px; letter-spacing:0; text-transform:none; }
#userDropdownMenu a:hover,#userDropdownMenu a:focus-visible { background:#f0e9de!important; }
#userDropdownMenu button { color:#b33440!important; }
#userDropdownMenu button:hover,#userDropdownMenu button:focus-visible { background:#fcebed!important; }
#userDropdownMenu .account-heading { padding:10px 12px 14px; border-bottom:1px solid #e9e1d6; margin-bottom:6px; }
#userDropdownMenu .account-heading strong { display:block; color:#26373d!important; font-size:14px; overflow-wrap:anywhere; }
#userDropdownMenu .account-heading small { display:block; color:#69777b!important; font-size:11px; margin-top:4px; }
#userDropdownMenu :focus-visible { outline:2px solid #967343; outline-offset:-2px; }
</style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col md:flex-row relative overflow-x-hidden">
    <!-- Background glowing accents -->
    <div class="absolute w-[500px] h-[500px] top-10 left-10 bg-green/5 dark:bg-green/5 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="absolute w-[500px] h-[500px] bottom-10 right-10 bg-brass/5 dark:bg-brass/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-[#fcfbf9] dark:bg-surface border-r border-stone-200 dark:border-border flex flex-col z-10 shrink-0 transition-all duration-300">
        <div class="p-6 border-b border-stone-200 dark:border-border flex items-center justify-between transition-colors duration-300">
            <div class="flex items-center space-x-3">
                <span class="text-[9px] text-[#0b0b0b] bg-brass font-black tracking-wider px-2 py-1 rounded">
                    ADMIN
                </span>
                <span class="text-lg font-serif text-stone-850 dark:text-[#F5EFE4] tracking-wide uppercase transition-colors duration-300">
                    Nuvara Portal
                </span>
            </div>
        </div>

        <nav class="flex-grow p-4 space-y-1 overflow-y-auto">
            <!-- Dashboard Link -->
            <a 
                href="{{ route('admin.dashboard') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/dashboard') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">📊</span>
                <span>Dashboard</span>
            </a>

            <!-- Products Link -->
            <a 
                href="{{ route('admin.products') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/products*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">📦</span>
                <span>Products</span>
            </a>

            <!-- Categories Link -->
            <a 
                href="{{ route('admin.categories') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/categories*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🏷️</span>
                <span>Categories</span>
            </a>

            <!-- Banners & Sliders Link -->
            <a 
                href="{{ route('admin.banners') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/banners*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🖼️</span>
                <span>Sliders & Banners</span>
            </a>

            <!-- Trust Features Link -->
            <a 
                href="{{ route('admin.features') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/features*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🛡️</span>
                <span>Trust Features</span>
            </a>

            <!-- Flash Sale Link -->
            <a 
                href="{{ route('admin.flash-sale') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/flash-sale*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">⚡</span>
                <span>Flash Sale</span>
            </a>

            <!-- Testimonials Link -->
            <a 
                href="{{ route('admin.testimonials') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/testimonials*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">💬</span>
                <span>Testimonials</span>
            </a>

            <!-- About Page Link -->
            <a 
                href="{{ route('admin.pages.about') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/pages/about*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">📖</span>
                <span>About Page</span>
            </a>

            <!-- Contact Page Link -->
            <a 
                href="{{ route('admin.pages.contact') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/pages/contact*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">📞</span>
                <span>Contact Page</span>
            </a>

            <!-- FAQs Link -->
            <a 
                href="{{ route('admin.faqs') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/faqs*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">❓</span>
                <span>FAQs</span>
            </a>

            <!-- Orders Link -->
            <a 
                href="{{ route('admin.orders') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/orders*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🛍️</span>
                <span>Orders</span>
            </a>

            <!-- Coupons Link -->
            <a 
                href="{{ route('admin.coupons') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/coupons*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🎟️</span>
                <span>Coupons</span>
            </a>

            <!-- Users Link -->
            <a 
                href="{{ route('admin.users') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/users*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">👥</span>
                <span>Users</span>
            </a>

            <!-- Profile Link -->
            <a 
                href="{{ route('admin.profile') }}" 
                class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/profile*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">⚙️</span>
                <span>Profile</span>
            </a>
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div class="p-4 border-t border-white/10 bg-[#364347] dark:bg-[#364347] transition-colors duration-300">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="w-full py-3 bg-[#364347] dark:bg-[#364347] border border-white/15 text-red-400 hover:bg-[#46565a] hover:border-red-300 hover:text-red-300 text-xs font-bold uppercase tracking-widest rounded-lg transition-all duration-300 transform active:scale-[0.98]"
                >
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0 z-10">
        <!-- Top Navbar -->
        <header class="h-16 border-b border-stone-200 dark:border-border bg-white/80 dark:bg-surface/40 backdrop-blur-md flex items-center justify-between px-6 md:px-8 relative z-40 transition-colors duration-300">
            <div class="flex items-center space-x-2 text-xs font-bold text-gray-500 uppercase tracking-widest">
                <span>System</span>
                <span>/</span>
                <span class="text-brass">@yield('header_title', 'Overview')</span>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Theme Toggle Button -->
                <button 
                    onclick="toggleThemeMode()" 
                    class="flex items-center justify-center w-9 h-9 bg-black/5 dark:bg-black/40 hover:bg-brass/10 dark:hover:bg-brass/5 border border-stone-200 dark:border-brass/20 hover:border-brass/40 dark:hover:border-brass/40 rounded-lg transition-all duration-200 focus:outline-none"
                    id="themeToggleBtn"
                    title="Toggle Theme"
                >
                    <span id="themeToggleIcon" class="text-sm">🌙</span>
                </button>

                <div class="relative">
                    <button 
                        onclick="toggleUserDropdown(event)" 
                        class="flex items-center space-x-3 bg-black/5 dark:bg-black/40 hover:bg-brass/10 dark:hover:bg-brass/5 border border-stone-200 dark:border-brass/20 hover:border-brass/40 dark:hover:border-brass/40 px-4 py-2 rounded-lg transition-colors focus:outline-none"
                        id="userDropdownBtn"
                    >
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                        <span style="color:#ffffff!important" class="admin-user-name text-xs font-bold font-sans">{{ Auth::user()->name }}</span>
                        <span style="color:#ffffff!important" class="admin-user-arrow text-[10px]">▼</span>
                    </button>
                    
                    <div 
                        id="userDropdownMenu" 
                        class="hidden absolute right-0 mt-2 text-left"
                    >
                        <div class="account-heading"><strong>{{ Auth::user()->name }}</strong><small>Manage your admin account</small></div>
                        <a 
                            href="{{ route('admin.profile') }}" 
                            class="flex items-center space-x-2.5 px-4 py-2.5 text-xs text-stone-600 dark:text-gray-300 hover:text-brass hover:bg-brass/5 transition-colors font-bold uppercase tracking-wider"
                        >
                            <span>⚙️</span>
                            <span>Profile Settings</span>
                        </a>
                        
                        <div class="border-t border-brass/10 my-1"></div>
                        
                        <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full flex items-center space-x-2.5 px-4 py-2.5 text-xs text-red-500 dark:text-red-400 hover:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-950/10 transition-colors text-left font-bold uppercase tracking-wider"
                            >
                                <span>🚪</span>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page View Body -->
        <main class="flex-grow p-6 md:p-8 overflow-y-auto max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div id="admin-success-toast" class="fixed top-6 right-6 z-[100] flex items-center gap-3 max-w-sm rounded-2xl bg-emerald-950/95 border border-emerald-400/30 px-5 py-4 text-emerald-200 text-sm font-semibold shadow-2xl backdrop-blur animate-[slideIn_.3s_ease-out]">
                    <span class="grid h-7 w-7 place-items-center rounded-full bg-emerald-400 text-emerald-950">✓</span><span>{{ session('success') }}</span><button onclick="document.getElementById('admin-success-toast').remove()" class="ml-auto text-emerald-300 hover:text-white">×</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-950/40 border border-red-900/50 text-red-400 text-xs font-semibold animate-pulse">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <style>@keyframes slideIn{from{transform:translateY(-12px);opacity:0}to{transform:translateY(0);opacity:1}}</style><script>setTimeout(()=>document.getElementById('admin-success-toast')?.remove(),4500)</script>

    <!-- Custom Compact Delete Modal (Matches reference image) -->
    <div id="nuvara-delete-modal-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div id="nuvara-delete-modal-card" class="bg-white dark:bg-[#141414] border border-gray-100 dark:border-border text-center rounded-[28px] p-6 sm:p-7 max-w-[340px] w-full shadow-2xl relative transform transition-all duration-200 scale-95 opacity-0">
            
            <!-- Red Trash Can Icon with Sparkles -->
            <div class="mx-auto w-14 h-14 mb-3.5 flex items-center justify-center relative select-none">
                <svg class="w-10 h-10 text-[#E05252]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                <!-- Sparkle Dots -->
                <span class="absolute -top-1 left-0 text-[10px] text-[#E05252]">✦</span>
                <span class="absolute top-1 -right-1 text-[8px] text-[#E05252]">✦</span>
                <span class="absolute bottom-0 -left-1 text-[8px] text-[#E05252]">✦</span>
                <span class="absolute bottom-2 -right-1.5 text-[10px] text-[#E05252]">✦</span>
            </div>

            <!-- Title -->
            <h3 id="nuvara-modal-title" class="text-base font-bold text-gray-900 dark:text-white mb-1.5 leading-snug">
                Confirm Deletion?
            </h3>

            <!-- Description -->
            <p id="nuvara-modal-desc" class="text-[12px] text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                Are you sure you want to delete this item?<br>This action cannot be undone.
            </p>

            <!-- Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <button 
                    type="button" 
                    onclick="closeNuvaraDeleteModal()" 
                    class="py-2.5 px-4 rounded-full border-2 border-[#E05252] text-[#E05252] bg-transparent hover:bg-[#E05252]/10 font-bold text-xs transition-all duration-150 active:scale-95"
                >
                    Cancel
                </button>

                <button 
                    type="button" 
                    id="nuvara-confirm-delete-btn" 
                    class="py-2.5 px-4 rounded-full bg-[#E05252] hover:bg-[#c93f3f] text-white font-bold text-xs shadow-md transition-all duration-150 active:scale-95"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        // Init theme state from localStorage
        const htmlElement = document.documentElement;
        const savedTheme = localStorage.getItem('admin-theme') || 'dark';
        if (savedTheme === 'light') {
            htmlElement.classList.add('light');
            htmlElement.classList.remove('dark');
            const icon = document.getElementById('themeToggleIcon');
            if (icon) icon.innerText = '☀️';
        } else {
            htmlElement.classList.add('dark');
            htmlElement.classList.remove('light');
            const icon = document.getElementById('themeToggleIcon');
            if (icon) icon.innerText = '🌙';
        }

        function toggleThemeMode() {
            if (htmlElement.classList.contains('light')) {
                htmlElement.classList.remove('light');
                htmlElement.classList.add('dark');
                localStorage.setItem('admin-theme', 'dark');
                document.getElementById('themeToggleIcon').innerText = '🌙';
            } else {
                htmlElement.classList.remove('dark');
                htmlElement.classList.add('light');
                localStorage.setItem('admin-theme', 'light');
                document.getElementById('themeToggleIcon').innerText = '☀️';
            }
        }

        function toggleUserDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('userDropdownMenu');
            menu.classList.toggle('hidden');
        }
        
        window.addEventListener('click', function(event) {
            const menu = document.getElementById('userDropdownMenu');
            const btn = document.getElementById('userDropdownBtn');
            if (menu && !menu.classList.contains('hidden')) {
                if (!btn.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            }
        });

        // Compact Delete Modal Controller (matching user reference image)
        let activeDeleteForm = null;

        function confirmDelete(event, itemName = '') {
            event.preventDefault();
            activeDeleteForm = event.target.closest('form');

            const title = document.getElementById('nuvara-modal-title');
            const overlay = document.getElementById('nuvara-delete-modal-overlay');
            const card = document.getElementById('nuvara-delete-modal-card');

            if (itemName && itemName !== 'this item') {
                title.innerText = `Confirm ${itemName} Deletion?`;
            } else {
                title.innerText = 'Confirm Deletion?';
            }

            overlay.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);

            return false;
        }

        function closeNuvaraDeleteModal() {
            const overlay = document.getElementById('nuvara-delete-modal-overlay');
            const card = document.getElementById('nuvara-delete-modal-card');

            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
                activeDeleteForm = null;
            }, 150);
        }

        document.getElementById('nuvara-confirm-delete-btn')?.addEventListener('click', function() {
            if (activeDeleteForm) {
                activeDeleteForm.submit();
            }
        });
    </script>
</body>
</html>
