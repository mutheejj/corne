{{-- Hero Section --}}
<section class="relative min-h-[500px] sm:min-h-[600px] overflow-hidden flex items-center pt-28 pb-16">
    {{-- Background Slider --}}
    <div class="hero-slider absolute inset-0 z-0">
        <div class="hero-slide active" style="background-image: url('{{ asset('images/hero/slide1.jpg') }}')"></div>
        <div class="hero-slide" style="background-image: url('{{ asset('images/hero/slide2.jpg') }}')"></div>
        <div class="hero-slide" style="background-image: url('{{ asset('images/hero/slide3.jpg') }}')"></div>
    </div>
    {{-- Dark Overlay --}}
    <div class="absolute inset-0 z-[1] bg-gradient-to-b from-navy-950/60 via-navy-950/40 to-navy-950/60"></div>

    {{-- Slider Dots --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
        <button class="hero-dot active" data-slide="0"></button>
        <button class="hero-dot" data-slide="1"></button>
        <button class="hero-dot" data-slide="2"></button>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 w-full text-center">
                <div class="inline-flex items-center gap-2 badge badge-orange mb-6 animate-fade-in-down">
                    <span class="notification-dot"></span>
                    <span>Trusted by universities nationwide</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white leading-tight mb-6 animate-fade-in-up">
                    Secure Online Voting for
                    <span class="gradient-text block mt-2">University Elections</span>
                </h1>

                <p class="text-lg text-white/70 mb-8 max-w-2xl mx-auto animate-fade-in-up delay-200">
                    Cornelect ensures election integrity, boosts voter engagement, and saves serious hours. Built specifically for universities — transparent, secure, and easy to use.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up delay-300">
                    <a href="{{ route('register') }}" class="btn-primary justify-center">
                        Start Voting Now
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('features') }}" class="btn-secondary justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                        Explore Features
                    </a>
                </div>

                {{-- Trust Indicators --}}
                <div class="flex flex-wrap items-center gap-x-8 gap-y-4 mt-8 sm:mt-12 justify-center animate-fade-in-up delay-500">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                        <span class="text-white/60 text-sm">256-bit Encryption</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                        <span class="text-white/60 text-sm">Anonymous Voting</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                        <span class="text-white/60 text-sm">Real-time Results</span>
                    </div>
                </div>
        </div>
    </div>

</section>
