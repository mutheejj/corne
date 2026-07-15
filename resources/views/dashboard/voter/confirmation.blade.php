@extends('layouts.dashboard')

@section('page-title', 'Vote Confirmation')

@section('sidebar-nav')
    @include('partials.sidebar-voter')
@endsection

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
        <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
        </div>
        <h2 class="text-2xl font-bold text-navy-950">Vote Cast Successfully!</h2>
        <p class="text-slate-500 mt-2">Your vote for {{ $position->title }} has been recorded.</p>

        <div class="mt-6 p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-500 mb-1">Your Verification Code</p>
            <p class="text-xl font-mono font-bold text-orange-600">{{ $voteRecord->verification_code }}</p>
            <p class="text-xs text-slate-400 mt-2">Save this code to verify your vote later.</p>
        </div>

        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('voter.elections.show', $election) }}" class="px-4 py-2 bg-navy-950 text-white rounded-lg text-sm font-medium hover:bg-navy-900">Back to Election</a>
            <a href="{{ route('voter.dashboard') }}" class="px-4 py-2 text-slate-500 hover:text-slate-700 text-sm font-medium">Dashboard</a>
        </div>
    </div>
</div>
@endsection
