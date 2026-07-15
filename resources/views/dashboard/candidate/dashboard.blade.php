@extends('layouts.dashboard')

@section('page-title', 'Candidate Dashboard')

@section('sidebar-nav')
    <a href="{{ route('candidate.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.dashboard') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        Dashboard
    </a>
    <a href="{{ route('candidate.profile') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.profile') || request()->routeIs('candidate.profile.update') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Campaign Profile
    </a>
    <a href="{{ route('candidate.election') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.election') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h8"/><path d="M4 6h16"/><path d="M4 18h16"/></svg>
        My Election
    </a>
    <a href="{{ route('candidate.position') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.position') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V8a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v12"/><path d="M8 20h8"/><path d="M12 6v14"/></svg>
        My Position
    </a>
    <a href="{{ route('candidate.results') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.results') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M11 16V5"/><path d="M15 16v-3"/><path d="M19 16V8"/></svg>
        Results
    </a>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Status Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-navy-950">Welcome, {{ auth()->user()->name }}</h2>
                <p class="text-slate-500 mt-1">{{ $candidate->position->title }} &middot; {{ $candidate->election->title }}</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $candidate->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                {{ $candidate->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $candidate->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
            ">
                {{ ucfirst($candidate->status) }}
            </span>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500 font-medium">Election Status</p>
            <p class="text-2xl font-bold text-navy-950 mt-2">{{ ucfirst($candidate->election->status) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500 font-medium">Total Votes</p>
            <p class="text-2xl font-bold text-navy-950 mt-2">{{ $candidate->vote_count }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500 font-medium">Vote Percentage</p>
            <p class="text-2xl font-bold text-navy-950 mt-2">{{ $candidate->vote_percentage }}%</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-navy-950 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('candidate.profile') }}" class="px-4 py-2 bg-navy-950 text-white rounded-lg text-sm font-medium hover:bg-navy-900">Edit Profile</a>
            <a href="{{ route('candidate.election') }}" class="px-4 py-2 bg-slate-100 text-navy-950 rounded-lg text-sm font-medium hover:bg-slate-200">View Election</a>
            @if (!in_array($candidate->election->status, ['completed', 'cancelled']))
                <form method="POST" action="{{ route('candidate.withdraw') }}" onsubmit="return confirm('Are you sure you want to withdraw your candidacy?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100">Withdraw Candidacy</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
