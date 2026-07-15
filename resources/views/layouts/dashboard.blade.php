<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    {{-- Mobile sidebar overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden transition-opacity duration-300"></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside id="dashboard-sidebar" class="w-64 bg-navy-950 text-white flex-shrink-0 fixed lg:sticky top-0 left-0 z-40 h-screen lg:h-screen flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6 border-b border-navy-800 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <span class="text-2xl font-extrabold">Corn<span class="text-orange-500">elect</span></span>
                    </a>
                    <button id="sidebar-close" class="lg:hidden p-1 text-navy-400 hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                @yield('sidebar-nav')
            </nav>

            {{-- Profile section --}}
            @php
                $profileRoute = match(auth()->user()->role) {
                    'admin' => 'admin.dashboard',
                    'candidate' => 'candidate.dashboard',
                    default => 'voter.profile',
                };
            @endphp
            <div class="p-4 border-t border-navy-800 flex-shrink-0">
                <a href="{{ route($profileRoute) }}" class="flex items-center gap-3 mb-3 p-2 rounded-lg hover:bg-navy-800 transition-colors group">
                    <div class="relative shrink-0">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full ring-2 ring-navy-700 group-hover:ring-orange-500 transition-all duration-300">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full ring-2 ring-navy-950"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate group-hover:text-orange-400 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-navy-400 truncate">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-navy-400 hover:text-orange-500 hover:bg-navy-800 transition-all duration-300 group">
                        <i data-lucide="log-out" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 w-full">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-slate-200 px-4 lg:px-6 py-4 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-3 min-w-0">
                    <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors flex-shrink-0">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="text-xl font-bold text-navy-950 truncate">@yield('page-title', 'Dashboard')</h1>
                </div>
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

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <script>
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('dashboard-sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var toggle = document.getElementById('sidebar-toggle');
            var close = document.getElementById('sidebar-close');

            function openSidebar() {
                if (sidebar) { sidebar.classList.remove('-translate-x-full'); }
                if (overlay) { overlay.classList.remove('hidden'); }
            }
            function closeSidebar() {
                if (sidebar) { sidebar.classList.add('-translate-x-full'); }
                if (overlay) { overlay.classList.add('hidden'); }
            }

            if (toggle) toggle.addEventListener('click', openSidebar);
            if (close) close.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        });
    </script>

    @stack('scripts')
</body>
</html>
