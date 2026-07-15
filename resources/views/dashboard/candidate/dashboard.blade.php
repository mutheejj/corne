@extends('layouts.dashboard')

@section('page-title', 'Candidate Dashboard')

@section("sidebar-nav")
    @include("partials.sidebar-candidate")
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
