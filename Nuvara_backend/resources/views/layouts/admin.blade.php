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
</head>
<body class="font-sans antialiased min-h-screen flex flex-col md:flex-row relative overflow-x-hidden">
    <!-- Sophisticated background glowing accents -->
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

        <nav class="flex-grow p-4 space-y-1">
            <!-- Dashboard Link -->
            <a 
                href="{{ route('admin.dashboard') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/dashboard') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">📊</span>
                <span>Dashboard</span>
            </a>

            <!-- Products Link -->
            <a 
                href="{{ route('admin.products') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/products*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">📦</span>
                <span>Products</span>
            </a>

            <!-- Categories Link -->
            <a 
                href="{{ route('admin.categories') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/categories*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🏷️</span>
                <span>Categories</span>
            </a>

            <!-- Orders Link -->
            <a 
                href="{{ route('admin.orders') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/orders*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🛍️</span>
                <span>Orders</span>
            </a>

            <!-- Coupons Link -->
            <a 
                href="{{ route('admin.coupons') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/coupons*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">🎟️</span>
                <span>Coupons</span>
            </a>

            <!-- Users Link -->
            <a 
                href="{{ route('admin.users') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/users*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">👥</span>
                <span>Users</span>
            </a>

            <!-- Profile Link -->
            <a 
                href="{{ route('admin.profile') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->is('admin/profile*') ? 'bg-brass text-black shadow-lg shadow-brass/10' : 'text-stone-600 dark:text-gray-400 hover:bg-stone-200/50 dark:hover:bg-white/5 hover:text-stone-900 dark:hover:text-white' }}"
            >
                <span class="text-sm">⚙️</span>
                <span>Profile</span>
            </a>
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div class="p-4 border-t border-stone-200 dark:border-border bg-stone-100 dark:bg-black/25 transition-colors duration-300">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="w-full py-3 border border-red-900/40 text-red-400 hover:bg-red-950/20 text-xs font-bold uppercase tracking-widest rounded-lg transition-all duration-300 transform active:scale-[0.98]"
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
                        <span class="text-xs text-stone-700 dark:text-gray-300 font-bold font-sans">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-stone-500 dark:text-gray-500">▼</span>
                    </button>
                    
                    <div 
                        id="userDropdownMenu" 
                        class="hidden absolute right-0 mt-2 w-48 rounded-xl glass-card py-2 z-50 text-left border border-brass/25"
                    >
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
                <div class="mb-6 p-4 rounded-xl bg-green-950/40 border border-green-900/50 text-green-400 text-xs font-semibold animate-pulse">
                    {{ session('success') }}
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
    </script>
</body>
</html>
