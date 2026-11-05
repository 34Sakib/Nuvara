<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuvara Admin — Portal Gateway</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 46'%3E%3Cpath fill='%23C5A880' d='M25.9 44.9c-.7.8-2 .4-2-.7V33.9c0-1.2-1-2.2-2.3-2.2H10.3c-.9 0-1.5-1-.9-1.8l7.5-10.5c1.1-1.5 0-3.6-1.8-3.6H1.2c-.9 0-1.4-1-.9-1.8L10 .5c.2-.3.6-.5.9-.5h28.9c.9 0 1.5 1 .9 1.8L33.2 12.3c-1.1 1.5 0 3.6 1.8 3.6h11.4c.9 0 1.5 1.1.9 1.8L25.9 44.9z'/%3E%3C/svg%3E">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brass: {
                            50: '#FBF8F3',
                            100: '#F5EFE4',
                            200: '#EBDDC8',
                            300: '#DFC6A2',
                            400: '#D2B383',
                            DEFAULT: '#C5A880',
                            600: '#B08F62',
                            700: '#917147',
                            800: '#725633',
                            900: '#543E24'
                        },
                        sage: {
                            50: '#F4F7F5',
                            100: '#E6EDE8',
                            200: '#CFDDCF',
                            500: '#5A7A6B',
                            700: '#2E4C3E',
                            800: '#1F3A2E'
                        },
                        ivory: {
                            DEFAULT: '#FAF8F5',
                            soft: '#F4EFEA',
                            pure: '#FFFFFF'
                        }
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
        /* Base page styling - Strictly 100% Light, Luminous & Ethereal */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            background-color: #FAF8F5;
            color: #2D3732;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Luminous mesh background canvas */
        .ambient-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: 
                radial-gradient(at 0% 0%, rgba(246, 238, 226, 0.8) 0px, transparent 55%),
                radial-gradient(at 100% 0%, rgba(230, 244, 238, 0.75) 0px, transparent 55%),
                radial-gradient(at 100% 100%, rgba(254, 245, 235, 0.85) 0px, transparent 55%),
                radial-gradient(at 0% 100%, rgba(236, 244, 249, 0.7) 0px, transparent 55%),
                linear-gradient(135deg, #FAF8F5 0%, #F5F1EB 50%, #FAF8F5 100%);
            overflow: hidden;
            pointer-events: none;
        }

        /* Subtle luxury grain/texture overlay to simulate museum paper & glass depth */
        .ambient-mesh::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.035;
            background-image: radial-gradient(#917147 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Floating luminous glass orbs */
        .glow-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(80px);
            opacity: 0.65;
            pointer-events: none;
            mix-blend-mode: multiply;
            will-change: transform;
        }

        .orb-champagne {
            width: 580px;
            height: 580px;
            background: radial-gradient(circle, rgba(233, 210, 175, 0.7) 0%, rgba(247, 232, 210, 0.3) 50%, transparent 75%);
            top: -160px;
            left: -140px;
            animation: orbFloat1 22s ease-in-out infinite alternate;
        }

        .orb-mint {
            width: 620px;
            height: 620px;
            background: radial-gradient(circle, rgba(197, 226, 216, 0.65) 0%, rgba(222, 243, 236, 0.25) 50%, transparent 75%);
            bottom: -180px;
            right: -160px;
            animation: orbFloat2 26s ease-in-out infinite alternate;
        }

        .orb-rose {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(249, 225, 217, 0.6) 0%, rgba(254, 241, 236, 0.2) 55%, transparent 75%);
            top: 40%;
            right: 15%;
            animation: orbFloat3 19s ease-in-out infinite alternate;
        }

        .orb-azure {
            width: 440px;
            height: 440px;
            background: radial-gradient(circle, rgba(220, 237, 248, 0.55) 0%, rgba(238, 246, 252, 0.2) 55%, transparent 75%);
            bottom: 10%;
            left: 10%;
            animation: orbFloat4 24s ease-in-out infinite alternate;
        }

        @keyframes orbFloat1 {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(60px, 50px) scale(1.08); }
            100% { transform: translate(-30px, 80px) scale(0.96); }
        }

        @keyframes orbFloat2 {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-70px, -60px) scale(1.06); }
            100% { transform: translate(40px, -40px) scale(0.94); }
        }

        @keyframes orbFloat3 {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-50px, 40px) scale(1.1); }
            100% { transform: translate(30px, -50px) scale(0.92); }
        }

        @keyframes orbFloat4 {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -40px) scale(1.05); }
            100% { transform: translate(-40px, 30px) scale(0.95); }
        }

        /* Pure Glassmorphic Master Card */
        .glassmorphic-card {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-top: 1.5px solid rgba(255, 255, 255, 1);
            border-left: 1.5px solid rgba(255, 255, 255, 0.95);
            box-shadow: 
                0 30px 60px -15px rgba(184, 160, 130, 0.18),
                0 15px 30px -10px rgba(90, 122, 107, 0.08),
                0 1px 3px 0 rgba(0, 0, 0, 0.02),
                inset 0 1px 1px 0 rgba(255, 255, 255, 1),
                inset 0 -1px 2px 0 rgba(197, 168, 128, 0.12);
            border-radius: 28px;
            position: relative;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glassmorphic-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 28px;
            pointer-events: none;
            box-shadow: inset 0 0 0 1px rgba(229, 214, 194, 0.35);
        }

        /* Glassmorphic nested panels & badges */
        .glass-pill {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 
                0 4px 14px -2px rgba(197, 168, 128, 0.16),
                inset 0 1px 1px rgba(255, 255, 255, 0.95);
        }

        /* Glass Inputs */
        .glass-input-wrapper {
            position: relative;
            transition: all 0.25s ease;
        }

        .glass-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(222, 212, 199, 0.65);
            color: #24352D;
            border-radius: 14px;
            padding: 13px 16px 13px 44px;
            font-size: 0.875rem;
            font-weight: 500;
            outline: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 
                0 2px 6px rgba(184, 160, 130, 0.05),
                inset 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .glass-input::placeholder {
            color: #9AA69F;
            font-weight: 400;
        }

        .glass-input:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(197, 168, 128, 0.6);
        }

        .glass-input:focus {
            background: #FFFFFF;
            border-color: #C5A880;
            box-shadow: 
                0 0 0 4px rgba(197, 168, 128, 0.18),
                0 8px 18px -4px rgba(197, 168, 128, 0.15);
        }

        /* Input Leading Icon Container */
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9C805B;
            pointer-events: none;
            transition: color 0.25s ease;
        }

        .glass-input:focus ~ .input-icon,
        .glass-input-wrapper:focus-within .input-icon {
            color: #725633;
        }

        /* Premium Brass Glass Button */
        .btn-luxury-brass {
            position: relative;
            background: linear-gradient(135deg, #D4BA94 0%, #C5A880 40%, #B39164 100%);
            color: #FFFFFF;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            box-shadow: 
                0 10px 25px -5px rgba(184, 145, 96, 0.42),
                0 4px 10px -2px rgba(184, 145, 96, 0.2),
                inset 0 1px 1.5px rgba(255, 255, 255, 0.65),
                inset 0 -1px 2px rgba(114, 86, 51, 0.25);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Shimmer reflection sweep on button hover */
        .btn-luxury-brass::before {
            content: '';
            position: absolute;
            top: 0;
            left: -120%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.35),
                transparent
            );
            transition: left 0.75s ease;
        }

        .btn-luxury-brass:hover::before {
            left: 120%;
        }

        .btn-luxury-brass:hover {
            transform: translateY(-1.5px);
            background: linear-gradient(135deg, #DEC5A2 0%, #C9AE87 40%, #BA9768 100%);
            box-shadow: 
                0 15px 32px -6px rgba(184, 145, 96, 0.52),
                0 6px 14px -3px rgba(184, 145, 96, 0.25),
                inset 0 1px 1.5px rgba(255, 255, 255, 0.75);
        }

        .btn-luxury-brass:active {
            transform: translateY(0.5px) scale(0.985);
            box-shadow: 
                0 6px 16px -3px rgba(184, 145, 96, 0.38),
                inset 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        /* Light Frosted Alerts */
        .glass-alert-error {
            background: rgba(255, 241, 242, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(253, 164, 175, 0.6);
            color: #9F1239;
            box-shadow: 0 4px 16px -2px rgba(225, 29, 72, 0.08);
        }

        .glass-alert-success {
            background: rgba(240, 253, 244, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(134, 239, 172, 0.6);
            color: #166534;
            box-shadow: 0 4px 16px -2px rgba(22, 101, 52, 0.08);
        }

        /* Smooth subtle entrance animation */
        @keyframes subtleFadeUp {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.985);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-subtle-up {
            animation: subtleFadeUp 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* Pulse green status ring */
        @keyframes statusPing {
            75%, 100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }

        .status-dot-ping {
            animation: statusPing 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 relative selection:bg-brass-200 selection:text-brass-900">

    <!-- Ambient Luminous Mesh & Animated Glowing Orbs (100% Light Palette) -->
    <div class="ambient-mesh">
        <div class="glow-orb orb-champagne"></div>
        <div class="glow-orb orb-mint"></div>
        <div class="glow-orb orb-rose"></div>
        <div class="glow-orb orb-azure"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-[460px] relative z-10 animate-subtle-up">
        
        <!-- Top Brand Emblem & Header -->
        <div class="text-center mb-6">
            <!-- Luxury Glass Brand Pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full glass-pill mb-4 select-none">
                <span class="relative flex h-2 w-2">
                    <span class="status-dot-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-[0.22em] text-brass-800">
                    Nuvara Core Portal
                </span>
                <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-brass-100 text-brass-700 font-bold border border-brass-300/40">
                    v2.4
                </span>
            </div>

            <!-- Brand Icon Crest (Nuvara Monogram) -->
            <div class="mx-auto w-16 h-16 rounded-2xl glass-pill flex items-center justify-center p-3 shadow-md mb-3 border border-white/80 group">
                <svg class="w-10 h-10 transition-transform duration-500 group-hover:scale-105" viewBox="0 0 48 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.946 44.938c-.664.845-2.021.375-2.021-.698V33.937a2.26 2.26 0 0 0-2.262-2.262H10.287c-.92 0-1.456-1.04-.92-1.788l7.48-10.471c1.07-1.497 0-3.578-1.842-3.578H1.237c-.92 0-1.456-1.04-.92-1.788L10.013.474c.214-.297.556-.474.92-.474h28.894c.92 0 1.456 1.04.92 1.788l-7.48 10.471c-1.07 1.498 0 3.579 1.842 3.579h11.377c.943 0 1.473 1.088.89 1.83L25.947 44.94z" fill="url(#crestGoldGrad)"/>
                    <defs>
                        <linearGradient id="crestGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#C5A880"/>
                            <stop offset="50%" stop-color="#B38E5E"/>
                            <stop offset="100%" stop-color="#7C5D33"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl font-serif text-[#1F2F26] tracking-tight font-normal">
                Admin Gateway
            </h1>
            <p class="text-xs text-[#63736A] mt-1.5 font-medium tracking-wide">
                Authorized management and commerce controls
            </p>
        </div>

        <!-- Master Glass Card -->
        <div class="glassmorphic-card p-7 sm:p-9">

            <!-- Flash Error Message -->
            @if(session('error'))
                <div class="glass-alert-error rounded-xl p-3.5 mb-5 flex items-start gap-3 text-xs font-semibold animate-subtle-up">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <div class="flex-1 leading-relaxed">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="glass-alert-success rounded-xl p-3.5 mb-5 flex items-start gap-3 text-xs font-semibold animate-subtle-up">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1 leading-relaxed">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if(isset($errors) && $errors->any())
                <div class="glass-alert-error rounded-xl p-3.5 mb-5 text-xs font-semibold animate-subtle-up">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5" id="adminLoginForm">
                @csrf

                <!-- Email Input Field -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-brass-800 uppercase tracking-wider mb-1.5 select-none">
                        Administrative Email
                    </label>
                    <div class="glass-input-wrapper">
                        <span class="input-icon">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            required 
                            autocomplete="username"
                            value="{{ old('email', 'admin@nuvara.com') }}"
                            placeholder="admin@nuvara.com"
                            class="glass-input"
                        >
                    </div>
                </div>

                <!-- Password Input Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-[11px] font-bold text-brass-800 uppercase tracking-wider select-none">
                            Access Key / Password
                        </label>
                        <span class="text-[10px] text-brass-700/80 font-medium">
                            Confidential
                        </span>
                    </div>
                    <div class="glass-input-wrapper">
                        <span class="input-icon">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0110 0v4" />
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••••••"
                            class="glass-input pr-11"
                        >
                        <!-- Toggle Password Visibility -->
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-brass-600 hover:text-brass-900 transition-colors rounded-md focus:outline-none"
                            title="Toggle password view"
                            id="togglePasswordBtn"
                        >
                            <!-- Eye Open Icon -->
                            <svg id="eyeIconOpen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Slash Icon (Hidden by default) -->
                            <svg id="eyeIconClosed" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Workstation Option & Helper -->
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center space-x-2.5 cursor-pointer select-none group">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            class="w-4 h-4 rounded border-brass-300 text-brass-600 focus:ring-brass-400 focus:ring-offset-0 transition-all bg-white/80 cursor-pointer"
                        >
                        <span class="text-[#52635B] group-hover:text-[#2E4C3E] font-medium transition-colors">
                            Remember session
                        </span>
                    </label>

                    <button 
                        type="button" 
                        onclick="autofillDemoCredentials()" 
                        class="text-[11px] font-semibold text-brass-800 hover:text-brass-900 transition-colors flex items-center gap-1 group bg-brass-50/70 hover:bg-brass-100/90 px-2.5 py-1 rounded-full border border-brass-200/80 shadow-2xs"
                        title="Click to fill test credentials"
                    >
                        <span>⚡</span>
                        <span>Fill Demo</span>
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="btn-luxury-brass w-full py-3.5 px-6 flex items-center justify-center gap-2 text-xs tracking-[0.14em]"
                    >
                        <span>Authenticate Portal</span>
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Divider with Badge -->
            <div class="relative my-6 select-none">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-brass-200/60"></div>
                </div>
                <div class="relative flex justify-center text-[10px]">
                    <span class="px-3 bg-white/70 backdrop-blur-md rounded-full text-brass-700 font-semibold uppercase tracking-wider border border-white/80 shadow-2xs">
                        High Security Gateway
                    </span>
                </div>
            </div>

            <!-- Security Assurance Notes -->
            <div class="grid grid-cols-2 gap-2 text-[11px] text-[#55665E]">
                <div class="flex items-center gap-2 p-2 rounded-xl bg-white/60 border border-white/80 shadow-2xs">
                    <span class="text-emerald-600 font-bold">🛡️</span>
                    <span class="truncate font-medium">256-Bit SSL Guard</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-xl bg-white/60 border border-white/80 shadow-2xs">
                    <span class="text-brass-700 font-bold">🔐</span>
                    <span class="truncate font-medium">Encrypted Tokens</span>
                </div>
            </div>

        </div>

        <!-- Footer Notice -->
        <div class="text-center mt-6 select-none space-y-1">
            <p class="text-[11px] text-[#697A72] font-medium">
                Nuvara Luxury Group &copy; {{ date('Y') }} &bull; All Rights Reserved
            </p>
            <p class="text-[10px] text-brass-700/75 tracking-wide">
                Restricted access &bull; Non-authorized attempts are monitored and logged
            </p>
        </div>

    </div>

    <!-- Client-side Interactive Scripts -->
    <script>
        // Password Visibility Toggle
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');

            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                pwdInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Demo Credentials Autofill Helper
        function autofillDemoCredentials() {
            const emailInput = document.getElementById('email');
            const pwdInput = document.getElementById('password');

            emailInput.value = 'admin@nuvara.com';
            pwdInput.value = 'adminpassword';

            // Visual feedback highlight
            emailInput.classList.add('ring-2', 'ring-brass-400');
            pwdInput.classList.add('ring-2', 'ring-brass-400');

            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-brass-400');
                pwdInput.classList.remove('ring-2', 'ring-brass-400');
            }, 800);
        }

        // Prevent accidental multiple submissions
        document.getElementById('adminLoginForm')?.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.style.opacity = '0.85';
                btn.style.pointerEvents = 'none';
                btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Verifying Access...</span>
                `;
            }
        });
    </script>
</body>
</html>