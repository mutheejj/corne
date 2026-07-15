<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-navy-950 text-white flex-shrink-0 hidden lg:flex flex-col">
            <div class="p-6 border-b border-navy-800">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-2xl font-extrabold">Corn<span class="text-orange-500">elect</span></span>
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                @yield('sidebar-nav')
            </nav>

            <div class="p-4 border-t border-navy-800">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-navy-400 truncate">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-navy-400 hover:text-orange-500 transition-colors">
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-bold text-navy-950">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    {{-- Notification Dropdown --}}
                    @php
                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                        $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->limit(5)->get();
                    @endphp
                    <div class="relative" id="notification-dropdown">
                        <button type="button" class="relative p-2 text-slate-500 hover:text-orange-600 transition-colors" onclick="document.getElementById('notification-panel').classList.toggle('hidden')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($unreadCount > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-orange-500 rounded-full">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                            @endif
                        </button>
                        <div id="notification-panel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-slate-200 z-50">
                            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                                <span class="font-semibold text-navy-950">Notifications</span>
                                @if ($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-orange-600 hover:text-orange-700">Mark all as read</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse ($recentNotifications as $notification)
                                    <div class="p-3 border-b border-slate-100 hover:bg-slate-50 {{ $notification->read_at ? '' : 'bg-orange-50' }}">
                                        <p class="text-sm font-medium text-navy-950">{{ $notification->title }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-sm text-slate-400">No notifications</div>
                                @endforelse
                            </div>
                            <div class="p-2 border-t border-slate-200 text-center">
                                <a href="#" class="text-xs text-orange-600 hover:text-orange-700">View all</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-orange-600">View Site</a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if (session('status'))
                <div class="mx-6 mt-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mx-6 mt-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f4f8', 100: '#d9e2ec', 200: '#bcccdc', 300: '#9fb3c8',
                            400: '#829ab1', 500: '#627d98', 600: '#486581', 700: '#334e68',
                            800: '#243b53', 900: '#102a43', 950: '#0a1628',
                        },
                        orange: {
                            50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa', 300: '#fdba74',
                            400: '#fb923c', 500: '#f97316', 600: '#ea580c', 700: '#c2410c',
                            800: '#9a3412', 900: '#7c2d12',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script>lucide.createIcons();</script>

    @stack('scripts')
</body>
</html>
