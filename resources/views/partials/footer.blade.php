{{-- Footer --}}
<footer class="bg-black relative overflow-hidden">
    <div class="glow-orb glow-orb-orange" style="width: 400px; height: 400px; top: -100px; right: -100px;"></div>
    <div class="glow-orb glow-orb-navy" style="width: 300px; height: 300px; bottom: -50px; left: -50px;"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-8">

        {{-- Top Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">

            {{-- Brand Column --}}
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center mb-6">
                    <span class="text-3xl font-extrabold text-white tracking-tight">Corn<span class="text-orange-500">elect</span></span>
                </a>
                <p class="text-white/60 text-sm leading-relaxed mb-6 max-w-md">
                    The secure, transparent, and easy-to-use online voting platform built specifically for universities. Empower your student democracy with confidence.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-10 h-10 rounded-lg glass flex items-center justify-center text-white/60 hover:text-orange-400 transition-colors" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.1 1.5 4.3c2.5 2.7 5.8 4.3 9.5 4.5C9.5 1.5 14.5.5 18 3.4c1.6-.2 3.1-.8 4.3-1.6.1 1.2-.5 2.4-1.3 3.2z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg glass flex items-center justify-center text-white/60 hover:text-orange-400 transition-colors" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg glass flex items-center justify-center text-white/60 hover:text-orange-400 transition-colors" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg glass flex items-center justify-center text-white/60 hover:text-orange-400 transition-colors" aria-label="LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                </div>
            </div>

            {{-- Platform Links --}}
            <div>
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Platform</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="footer-link text-sm">Home</a></li>
                    <li><a href="{{ route('features') }}" class="footer-link text-sm">Features</a></li>
                    <li><a href="{{ route('about') }}" class="footer-link text-sm">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link text-sm">Contact</a></li>
                </ul>
            </div>

            {{-- Account Links --}}
            <div>
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Account</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('login') }}" class="footer-link text-sm">Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="footer-link text-sm">Register as Voter</a></li>
                    <li><a href="{{ route('register.candidate') }}" class="footer-link text-sm">Register as Candidate</a></li>
                    <li><a href="{{ route('password.request') }}" class="footer-link text-sm">Forgot Password</a></li>
                </ul>
            </div>

            {{-- Legal Links --}}
            <div>
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('privacy') }}" class="footer-link text-sm">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="footer-link text-sm">Terms of Service</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link text-sm">Support</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-white/40 text-sm">&copy; {{ date('Y') }} Cornelect. All rights reserved. Built for universities, by students.</p>
            <div class="flex items-center gap-2 text-white/40 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>256-bit encryption secured</span>
            </div>
        </div>
    </div>
</footer>
