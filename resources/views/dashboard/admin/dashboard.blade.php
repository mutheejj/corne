@extends('layouts.dashboard')

@section('page-title', 'Admin Dashboard')

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-950">Welcome back, {{ auth()->user()->name }}</h2>
        <a href="{{ route('admin.elections.create') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
            Create Election
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200">
            <p class="text-sm text-slate-500">Total Elections</p>
            <p class="text-3xl font-bold text-navy-950 mt-1">{{ $stats['total_elections'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200">
            <p class="text-sm text-slate-500">Active Elections</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['active_elections'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200">
            <p class="text-sm text-slate-500">Total Voters</p>
            <p class="text-3xl font-bold text-navy-950 mt-1">{{ $stats['total_voters'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200">
            <p class="text-sm text-slate-500">Total Candidates</p>
            <p class="text-3xl font-bold text-navy-950 mt-1">{{ $stats['total_candidates'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200">
            <p class="text-sm text-slate-500">Pending Approvals</p>
            <p class="text-3xl font-bold text-orange-500 mt-1">{{ $stats['pending_candidates'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-200">
            <p class="text-sm text-slate-500">Total Votes</p>
            <p class="text-3xl font-bold text-navy-950 mt-1">{{ $stats['total_votes'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <h3 class="text-lg font-bold text-navy-950 mb-4">Recent Elections</h3>
            <div class="space-y-3">
                @forelse ($recentElections as $election)
                    <a href="{{ route('admin.elections.show', $election) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-50">
                        <div>
                            <p class="font-medium text-navy-950">{{ $election->title }}</p>
                            <p class="text-sm text-slate-500">{{ $election->status }}</p>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
                    </a>
                @empty
                    <p class="text-slate-400 text-sm">No elections yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <h3 class="text-lg font-bold text-navy-950 mb-4">Pending Candidates</h3>
            <div class="space-y-3">
                @forelse ($pendingCandidates as $candidate)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-50">
                        <div>
                            <p class="font-medium text-navy-950">{{ $candidate->user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $candidate->position->title ?? 'N/A' }}</p>
                        </div>
                        <a href="{{ route('admin.candidates.approve', $candidate) }}" class="text-green-600 hover:text-green-700 text-sm font-medium" onclick="event.preventDefault(); document.getElementById('approve-{{ $candidate->id }}').submit();">Approve</a>
                        <form id="approve-{{ $candidate->id }}" action="{{ route('admin.candidates.approve', $candidate) }}" method="POST" class="hidden">@csrf</form>
                    </div>
                @empty
                    <p class="text-slate-400 text-sm">No pending candidates.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
