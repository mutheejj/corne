@extends('layouts.auth')

@section('title', 'Forgot Password — Cornelect')

@section('auth-content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-navy-950 mb-2">Forgot Password?</h1>
        <p class="text-slate-500">Enter your email and we'll send you a reset link</p>
    </div>

    @if (session('status'))
        <div data-flash class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" data-validate class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-navy-950 mb-2">University Email</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="you@university.ac.ke" required autofocus>
            <p data-error class="hidden text-red-500 text-xs mt-1">This field is required</p>
        </div>

        <button type="submit" class="btn-primary w-full justify-center">
            Send Reset Link
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
        </button>
    </form>

    <p class="text-center text-sm text-slate-600 mt-6">
        Remember your password? <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-semibold">Sign In</a>
    </p>
@endsection

@section('auth-sidebar')
    <div class="animate-fade-in-right">
        <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">
            Reset Your <span class="gradient-text">Password</span>
        </h2>
        <p class="text-white/70 text-lg mb-8 leading-relaxed">
            Don't worry — it happens. Enter your university email and we'll send you a secure link to reset your password.
        </p>

        <div class="glass rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <p class="text-white/80 text-sm font-semibold">Secure Process</p>
            </div>
            <p class="text-white/60 text-sm">
                Password reset links are time-limited and can only be used once. For security, the link expires after 60 minutes.
            </p>
        </div>
    </div>
@endsection
