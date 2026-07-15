{{-- Header Navigation --}}
<header id="main-header" class="fixed top-0 left-0 right-0 z-50 glass-navy transition-all duration-300">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center group">
                <span class="text-2xl font-extrabold text-white tracking-tight group-hover:text-orange-400 transition-colors duration-300">Corn<span class="text-orange-500">elect</span></span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="nav-link text-white/80 hover:text-white text-sm font-medium">Home</a>
                <a href="{{ route('features') }}" class="nav-link text-white/80 hover:text-white text-sm font-medium">Features</a>
                <a href="{{ route('about') }}" class="nav-link text-white/80 hover:text-white text-sm font-medium">About</a>
                <a href="{{ route('contact') }}" class="nav-link text-white/80 hover:text-white text-sm font-medium">Contact</a>
            </div>

            {{-- Desktop CTA Buttons --}}
            <div class="hidden lg:flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-white/80 hover:text-orange-400 text-sm font-semibold transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="btn-primary text-sm">
                    Get Started
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="lg:hidden text-white p-2" aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </button>
        </div>
    </nav>
</header>

{{-- Mobile Menu Overlay --}}
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

{{-- Mobile Menu --}}
<aside id="mobile-menu" class="mobile-menu fixed top-0 left-0 bottom-0 w-80 max-w-[85vw] z-50 gradient-navy lg:hidden overflow-y-auto">
    <div class="p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <span class="text-xl font-extrabold text-white">Corn<span class="text-orange-500">elect</span></span>
            </div>
            <button id="mobile-menu-close" class="text-white/60 hover:text-white p-2" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>

        <nav class="flex flex-col gap-1 mb-8">
            <a href="{{ route('home') }}" class="text-white/80 hover:text-orange-400 hover:bg-white/5 px-4 py-3 rounded-lg text-base font-medium transition-all">Home</a>
            <a href="{{ route('features') }}" class="text-white/80 hover:text-orange-400 hover:bg-white/5 px-4 py-3 rounded-lg text-base font-medium transition-all">Features</a>
            <a href="{{ route('about') }}" class="text-white/80 hover:text-orange-400 hover:bg-white/5 px-4 py-3 rounded-lg text-base font-medium transition-all">About</a>
            <a href="{{ route('contact') }}" class="text-white/80 hover:text-orange-400 hover:bg-white/5 px-4 py-3 rounded-lg text-base font-medium transition-all">Contact</a>
        </nav>

        <div class="border-t border-white/10 pt-6 flex flex-col gap-3">
            <a href="{{ route('login') }}" class="btn-secondary w-full justify-center">Sign In</a>
            <a href="{{ route('register') }}" class="btn-primary w-full justify-center">
                Get Started
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</aside>
