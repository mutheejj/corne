@extends('layouts.dashboard')

@section('page-title', 'Voter Dashboard')

@section('sidebar-nav')
    <a href="{{ route('voter.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('voter.dashboard') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">Dashboard</a>
    <a href="{{ route('voter.elections.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('voter.elections*') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">Elections</a>
    <a href="{{ route('voter.vote-history') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('voter.vote-history') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">Vote History</a>
    <a href="{{ route('voter.profile') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('voter.profile') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">Profile</a>
@endsection

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-navy-950">Welcome, {{ auth()->user()->name }}</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-navy-950 mb-4">Active Elections</h3>
            <div class="space-y-3">
                @forelse ($activeElections as $election)
                    <a href="{{ route('voter.elections.show', $election) }}" class="block p-4 rounded-lg bg-slate-50 hover:bg-slate-100">
                        <p class="font-semibold text-navy-950">{{ $election->title }}</p>
                        <p class="text-sm text-slate-500">Ends: {{ $election->ends_at?->format('M d, Y H:i') }}</p>
                    </a>
                @empty
                    <p class="text-slate-400 text-sm">No active elections.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-navy-950 mb-4">Recent Votes</h3>
            <div class="space-y-3">
                @forelse ($voteRecords as $record)
                    <div class="p-4 rounded-lg bg-slate-50">
                        <p class="font-medium text-navy-950">{{ $record->election->title }}</p>
                        <p class="text-sm text-slate-500">Voted: {{ $record->voted_at->format('M d, Y H:i') }}</p>
                        <p class="text-xs text-orange-600 font-mono mt-1">{{ $record->verification_code }}</p>
                    </div>
                @empty
                    <p class="text-slate-400 text-sm">You haven't voted yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
