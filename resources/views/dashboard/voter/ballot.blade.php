@extends('layouts.dashboard')

@section('page-title', 'Ballot')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-2xl font-bold text-navy-950">{{ $position->title }}</h2>
        <p class="text-slate-500 mt-2">{{ $position->description }}</p>
    </div>

    <form method="POST" action="{{ route('voter.votes.cast', [$election, $position]) }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
            <h3 class="text-lg font-bold text-navy-950">Select a Candidate</h3>
            @foreach ($candidates as $candidate)
                <label class="flex items-center gap-4 p-4 rounded-lg border border-slate-200 hover:border-orange-500 cursor-pointer transition-colors">
                    <input type="radio" name="candidate_id" value="{{ $candidate->id }}" class="w-5 h-5 text-orange-500 focus:ring-orange-500" @if(!($election->settings && $election->settings->allow_abstain)) required @endif>
                    <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->user->name }}" class="w-12 h-12 rounded-full">
                    <div class="flex-1">
                        <p class="font-semibold text-navy-950">{{ $candidate->user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $candidate->slogan ?? 'No slogan' }}</p>
                    </div>
                </label>
            @endforeach
            @if ($candidates->isEmpty())
                <p class="text-slate-400">No approved candidates for this position.</p>
            @endif
            @if ($election->settings && $election->settings->allow_abstain)
                <label class="flex items-center gap-4 p-4 rounded-lg border border-slate-200 hover:border-orange-500 cursor-pointer transition-colors">
                    <input type="radio" name="abstain" value="1" class="w-5 h-5 text-orange-500 focus:ring-orange-500">
                    <div class="flex-1">
                        <p class="font-semibold text-navy-950">Abstain</p>
                        <p class="text-sm text-slate-500">Choose not to vote for any candidate</p>
                    </div>
                </label>
            @endif
        </div>
        <div class="mt-6">
            <button type="submit" class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600">Cast Vote</button>
            <a href="{{ route('voter.elections.show', $election) }}" class="ml-3 text-slate-500 hover:text-slate-700">Cancel</a>
        </div>
    </form>
</div>
@endsection
