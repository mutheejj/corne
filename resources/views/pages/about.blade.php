@extends('layouts.app')

@section('title', 'About Us — Cornelect')
@section('meta-description', 'Learn about Cornelect — the secure online voting platform built for universities by students who understand the challenges of campus democracy.')

@section('content')

{{-- Hero --}}
<section class="relative pt-32 pb-20 gradient-mesh overflow-hidden">
    <div class="glow-orb glow-orb-orange" style="width: 500px; height: 500px; top: -150px; right: -100px;"></div>
    <div class="absolute inset-0 grid-pattern opacity-20"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="badge badge-orange mb-6 animate-fade-in-down">
            <span class="notification-dot"></span>
            <span>Our Story</span>
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 animate-fade-in-up">
            About <span class="gradient-text">Cornelect</span>
        </h1>
        <p class="text-white/70 text-lg lg:text-xl max-w-2xl mx-auto animate-fade-in-up delay-200">
            Built by students, for students. We're on a mission to make university elections secure, transparent, and accessible to everyone.
        </p>
    </div>
</section>

{{-- Mission Section --}}
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="reveal-left">
                <div class="badge badge-orange mb-4">Our Mission</div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-navy-950 mb-6">
                    Empowering University <span class="gradient-text">Democracy</span>
                </h2>
                <p class="text-slate-500 text-lg mb-6 leading-relaxed">
                    Cornelect was born from a simple observation: university elections deserved better. Paper ballots were slow, manual counting was error-prone, and student engagement was declining year after year.
                </p>
                <p class="text-slate-500 text-lg mb-6 leading-relaxed">
                    We set out to build a platform that combines bank-grade security with an intuitive user experience — making it easy for every student to have their voice heard, while giving administrators the tools they need to run fair and transparent elections.
                </p>
                <p class="text-slate-500 text-lg leading-relaxed">
                    Today, Cornelect serves thousands of students across multiple universities, processing elections with cryptographic integrity and real-time transparency.
                </p>
            </div>
            <div class="reveal-right">
                <div class="glass rounded-2xl p-8 shadow-2xl gradient-navy">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <p class="text-4xl font-extrabold text-orange-500 counter" data-counter="45000" data-suffix="+">0</p>
                            <p class="text-white/60 text-sm mt-2">Active Voters</p>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-extrabold text-orange-500 counter" data-counter="320" data-suffix="+">0</p>
                            <p class="text-white/60 text-sm mt-2">Elections Run</p>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-extrabold text-orange-500 counter" data-counter="12" data-suffix="">0</p>
                            <p class="text-white/60 text-sm mt-2">Universities</p>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-extrabold text-orange-500 counter" data-counter="98" data-suffix="%">0</p>
                            <p class="text-white/60 text-sm mt-2">Turnout Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Values Section --}}
<section class="section-padding bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 reveal">
            <div class="badge badge-navy mb-4">Our Values</div>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-navy-950 mb-4">
                What We <span class="gradient-text">Stand For</span>
            </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 feature-card reveal">
                <div class="icon-container icon-container-orange mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy-950 mb-3">Security First</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Every decision we make starts with security. From 256-bit encryption to cryptographic receipts, we never compromise on protecting your vote.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 feature-card reveal delay-100">
                <div class="icon-container icon-container-orange mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy-950 mb-3">Transparency</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Every vote is verifiable, every result is auditable, and every action is logged. We believe trust is built through openness, not obscurity.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 feature-card reveal delay-200">
                <div class="icon-container icon-container-orange mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy-950 mb-3">Accessibility</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Voting should be easy for everyone. Our platform works on any device, in any browser, with an interface designed for all students.</p>
            </div>
        </div>
    </div>
</section>

{{-- Team Section --}}
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 reveal">
            <div class="badge badge-orange mb-4">Our Team</div>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-navy-950 mb-4">
                Built by <span class="gradient-text">Students</span>
            </h2>
            <p class="text-slate-500 text-lg">A passionate team of developers, designers, and democracy advocates.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center reveal">
                <div class="w-24 h-24 rounded-full gradient-orange flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">DK</div>
                <h4 class="font-bold text-navy-950">David Kamau</h4>
                <p class="text-slate-400 text-sm">Founder & CEO</p>
            </div>
            <div class="text-center reveal delay-100">
                <div class="w-24 h-24 rounded-full bg-navy-700 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">AN</div>
                <h4 class="font-bold text-navy-950">Alice Njoroge</h4>
                <p class="text-slate-400 text-sm">CTO</p>
            </div>
            <div class="text-center reveal delay-200">
                <div class="w-24 h-24 rounded-full bg-navy-600 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">GM</div>
                <h4 class="font-bold text-navy-950">Grace Mwangi</h4>
                <p class="text-slate-400 text-sm">Head of Design</p>
            </div>
            <div class="text-center reveal delay-300">
                <div class="w-24 h-24 rounded-full bg-navy-500 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">SO</div>
                <h4 class="font-bold text-navy-950">Samuel Ochieng</h4>
                <p class="text-slate-400 text-sm">Security Lead</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
@include('partials.home._cta')

@endsection
