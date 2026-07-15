{{-- CTA Section --}}
<section class="relative py-20 lg:py-28 bg-white overflow-hidden">

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="reveal-scale">
            <div class="inline-flex items-center gap-2 badge badge-orange mb-6">
                <span class="notification-dot"></span>
                <span>Ready to get started?</span>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-extrabold text-navy-950 mb-6 leading-tight">
                Empower Your University's
                <span class="gradient-text block mt-2">Democracy Today</span>
            </h2>

            <p class="text-slate-500 text-lg mb-10 max-w-2xl mx-auto">
                Join thousands of students and administrators who trust Cornelect for secure, transparent, and engaging university elections. It's free to get started.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-primary justify-center text-base px-8 py-4">
                    Create Your Account
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="btn-secondary justify-center text-base px-8 py-4">
                    Contact Us
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </a>
            </div>

            {{-- Trust Badges --}}
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4 mt-12">
                <div class="flex items-center gap-2 text-slate-400 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                    No credit card required
                </div>
                <div class="flex items-center gap-2 text-slate-400 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                    Setup in minutes
                </div>
                <div class="flex items-center gap-2 text-slate-400 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                    24/7 support
                </div>
            </div>
        </div>
    </div>
</section>
