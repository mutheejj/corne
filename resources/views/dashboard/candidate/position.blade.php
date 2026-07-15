@extends('layouts.dashboard')

@section('page-title', 'My Position')

@section('sidebar-nav')
    <a href="{{ route('candidate.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.dashboard') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">Dashboard</a>
    <a href="{{ route('candidate.profile') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.profile') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">Campaign Profile</a>
    <a href="{{ route('candidate.election') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.election') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">My Election</a>
    <a href="{{ route('candidate.position') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.position') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">My Position</a>
    <a href="{{ route('candidate.results') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.results') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">Results</a>
@endsection

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-2xl font-bold text-navy-950">{{ $position->title }}</h3>
        <p class="text-slate-500 mt-2">{{ $position->description }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-bold text-navy-950 mb-4">Competitors</h4>
        <div class="space-y-3">
            @foreach ($competitors as $competitor)
                <div class="flex items-center gap-4 p-4 rounded-lg bg-slate-50">
                    <img src="{{ $competitor->photo_url }}" alt="{{ $competitor->user->name }}" class="w-12 h-12 rounded-full">
                    <div class="flex-1">
                        <p class="font-semibold text-navy-950">{{ $competitor->user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $competitor->slogan ?? 'No slogan' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $competitor->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $competitor->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $competitor->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                    ">{{ ucfirst($competitor->status) }}</span>
                </div>
            @endforeach
            @if ($competitors->isEmpty())
                <p class="text-slate-500 text-sm">No other approved candidates for this position.</p>
            @endif
        </div>
    </div>
</div>
@endsection
