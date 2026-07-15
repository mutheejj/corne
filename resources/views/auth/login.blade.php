@extends('layouts.auth')

@section('title', 'Sign In — Cornelect')

@section('auth-content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-navy-950 mb-2">Welcome Back</h1>
        <p class="text-slate-500">Sign in to your Cornelect account to continue</p>
    </div>

    {{-- Flash Messages --}}
    @if (session('status'))
        <div data-flash class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div data-flash class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" data-validate class="space-y-5">
        @csrf

        <div>
            <label for="identifier" class="block text-sm font-semibold text-navy-950 mb-2">Email or Student ID</label>
            <input type="text" id="identifier" name="identifier" class="form-input" placeholder="you@university.ac.ke or ABC123-1234/2023" required autofocus>
            <p data-error class="hidden text-red-500 text-xs mt-1">This field is required</p>
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-semibold text-navy-950">Password</label>
                <a href="{{ route('password.request') }}" class="text-xs text-orange-600 hover:text-orange-700 font-medium">Forgot password?</a>
            </div>
            <div class="relative">
                <input type="password" id="password" name="password" class="form-input pr-12" placeholder="Enter your password" required>
                <button type="button" data-password-toggle data-target="#password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500">
                    <svg data-password-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            <p data-error class="hidden text-red-500 text-xs mt-1">This field is required</p>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                <span class="text-sm text-slate-600">Remember me</span>
            </label>
        </div>

        <button type="submit" class="btn-primary w-full justify-center">
            Sign In
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
    </form>

    {{-- Divider --}}
    <div class="my-6 flex items-center gap-4">
        <div class="flex-1 h-px bg-slate-200"></div>
        <span class="text-slate-400 text-xs uppercase">or</span>
        <div class="flex-1 h-px bg-slate-200"></div>
    </div>

    {{-- Quick Links --}}
    <div class="space-y-3">
        <a href="{{ route('register') }}" class="block text-center text-sm text-slate-600 hover:text-orange-600 font-medium">
            Don't have an account? <span class="text-orange-600 font-semibold">Register as Voter</span>
        </a>
        <a href="{{ route('register.candidate') }}" class="block text-center text-sm text-slate-600 hover:text-orange-600 font-medium">
            Want to run for a position? <span class="text-orange-600 font-semibold">Register as Candidate</span>
        </a>
    </div>
@endsection

@section('auth-sidebar')
    <div class="animate-fade-in-right">
        <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">
            Your Vote, <span class="gradient-text">Your Voice</span>
        </h2>
        <p class="text-white/70 text-lg mb-8 leading-relaxed">
            Sign in to participate in university elections, view results, and manage your voting profile securely.
        </p>

        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Secure 256-bit encrypted voting</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Real-time election results</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Vote verification with receipts</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Anonymous and private ballots</p>
            </div>
        </div>

        <div class="mt-12 p-6 glass rounded-xl">
            <p class="text-white/80 text-sm italic mb-3">
                "Voting with Cornelect was seamless. I verified my vote was counted within seconds!"
            </p>
            <p class="text-white/50 text-xs">— Grace M., 3rd Year Student</p>
        </div>
    </div>
@endsection
