@extends('layouts.auth')

@section('title', 'Reset Password — Cornelect')

@section('auth-content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-navy-950 mb-2">Reset Password</h1>
        <p class="text-slate-500">Enter your new password below</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" data-validate class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token ?? '' }}">

        <div>
            <label for="email" class="block text-sm font-semibold text-navy-950 mb-2">University Email</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="you@university.ac.ke" value="{{ $email ?? old('email') }}" required autofocus>
            <p data-error class="hidden text-red-500 text-xs mt-1">Required</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-navy-950 mb-2">New Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" class="form-input pr-12" placeholder="Minimum 8 characters" required minlength="8">
                <button type="button" data-password-toggle data-target="#password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500">
                    <svg data-password-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            <div id="password-strength-bar" class="password-strength mt-2" style="width: 0;"></div>
            <p id="password-strength-text" class="text-xs text-slate-400 mt-1"></p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-navy-950 mb-2">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Re-enter new password" required>
            <p data-error class="hidden text-red-500 text-xs mt-1">Required</p>
        </div>

        <button type="submit" class="btn-primary w-full justify-center">
            Reset Password
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
        </button>
    </form>

    <p class="text-center text-sm text-slate-600 mt-6">
        Remember your password? <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-semibold">Sign In</a>
    </p>
@endsection

@section('auth-sidebar')
    <div class="animate-fade-in-right">
        <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">
            Choose a <span class="gradient-text">Strong Password</span>
        </h2>
        <p class="text-white/70 text-lg mb-8 leading-relaxed">
            For your security, choose a password that's at least 8 characters long with a mix of letters, numbers, and symbols.
        </p>

        <div class="glass rounded-xl p-6">
            <p class="text-white/80 text-sm font-semibold mb-4">Password tips:</p>
            <ul class="space-y-3">
                <li class="flex items-center gap-3 text-white/60 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/></svg>
                    Use at least 8 characters
                </li>
                <li class="flex items-center gap-3 text-white/60 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/></svg>
                    Mix uppercase and lowercase letters
                </li>
                <li class="flex items-center gap-3 text-white/60 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/></svg>
                    Include numbers and special characters
                </li>
                <li class="flex items-center gap-3 text-white/60 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/></svg>
                    Avoid using your name or student ID
                </li>
            </ul>
        </div>
    </div>
@endsection
