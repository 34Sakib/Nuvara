<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuvara Admin - Portal</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;0,9..144,800;1,9..144,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
                        surface: '#121212',
                        border: '#1f1f1f'
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
            background-color: #060606;
            color: #f5efe4;
        }
        .glass-card {
            background: rgba(18, 18, 18, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(197, 168, 128, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        input:focus {
            box-shadow: 0 0 15px rgba(197, 168, 128, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Sophisticated background glowing accents -->
    <div class="absolute w-[500px] h-[500px] -top-40 -left-40 bg-green/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute w-[500px] h-[500px] -bottom-40 -right-40 bg-brass/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md z-10 animate-fade-in">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <span class="text-[10px] text-brass uppercase font-black tracking-[0.25em] bg-brass/5 border border-brass/15 px-4.5 py-2 rounded-full select-none">
                Nuvara Control Panel
            </span>
            <h1 class="text-3xl md:text-4xl font-serif text-[#F5EFE4] mt-6 tracking-wide uppercase">
                Admin Gateway
            </h1>
            <p class="text-xs text-gray-500 mt-2 font-medium">
                Authorized personnel access only
            </p>
        </div>

        <div class="glass-card rounded-2xl p-8 shadow-2xl relative transition-all duration-300 hover:border-brass/35">
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-950/40 border border-red-900/50 text-red-400 text-xs font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-950/40 border border-green-900/50 text-green-400 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required 
                        placeholder="admin@nuvara.com"
                        class="w-full px-4 py-3 rounded-lg border border-border bg-black/50 text-gray-200 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass transition-all placeholder:text-gray-700"
                    >
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        placeholder="••••••••••••"
                        class="w-full px-4 py-3 rounded-lg border border-border bg-black/50 text-gray-200 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass transition-all placeholder:text-gray-700"
                    >
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-all duration-300 transform active:scale-[0.98] shadow-md hover:shadow-brass/10"
                    >
                        Sign In to Portal
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
