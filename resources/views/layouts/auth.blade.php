@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    {{-- Left Side - Form --}}
    <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-28 lg:py-20 bg-slate-50">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center mb-8 justify-center">
                <span class="text-3xl font-extrabold text-navy-950">Corn<span class="text-orange-500">elect</span></span>
            </a>

            @yield('auth-content')
        </div>
    </div>

    {{-- Right Side - Visual --}}
    <div class="hidden lg:flex lg:w-1/2 auth-sidebar items-center justify-center relative">
        <div class="glow-orb glow-orb-orange" style="width: 400px; height: 400px; top: 10%; right: -100px;"></div>
        <div class="glow-orb glow-orb-navy" style="width: 300px; height: 300px; bottom: 10%; left: -50px;"></div>
        <div class="absolute inset-0 dot-pattern opacity-30"></div>

        <div class="relative z-10 p-12 max-w-lg">
            @yield('auth-sidebar')
        </div>
    </div>
</div>
@endsection
