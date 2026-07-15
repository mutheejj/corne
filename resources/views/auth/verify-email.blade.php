@extends('layouts.auth')

@section('title', 'Verify Email — Cornelect')

@section('auth-content')
    <div class="mb-8 text-center">
        <div class="w-16 h-16 rounded-2xl gradient-orange flex items-center justify-center shadow-lg mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="m16 19 2 2 4-4"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-navy-950 mb-2">Verify Your Email</h1>
        <p class="text-slate-500">We've sent a verification link to your email address</p>
    </div>

    @if (session('status'))
        <div data-flash class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="p-6 rounded-xl bg-slate-50 border border-slate-200 mb-6">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            <div>
                <p class="text-navy-950 text-sm font-semibold mb-1">Check your inbox</p>
                <p class="text-slate-500 text-sm">
                    Click the verification link in the email we sent you to activate your account. Don't forget to check your spam folder.
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
        @csrf
        <button type="submit" class="btn-secondary w-full justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Resend Verification Email
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-500 hover:text-orange-600 font-medium w-full text-center">
                Sign out and try again
            </button>
        </form>
    </div>
@endsection

@section('auth-sidebar')
    <div class="animate-fade-in-right">
        <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">
            One More <span class="gradient-text">Step</span>
        </h2>
        <p class="text-white/70 text-lg mb-8 leading-relaxed">
            Email verification ensures that only legitimate university students can participate in elections, maintaining the integrity of every vote.
        </p>

        <div class="glass rounded-xl p-6">
            <p class="text-white/80 text-sm italic mb-3">
                "Email verification took less than a minute. I was voting in my first election right after!"
            </p>
            <p class="text-white/50 text-xs">— David K., Student Union President</p>
        </div>
    </div>
@endsection
