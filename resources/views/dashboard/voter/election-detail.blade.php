@extends('layouts.dashboard')

@section('page-title', $election->title)

@section('sidebar-nav')
    @include('partials.sidebar-voter')
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-2xl font-bold text-navy-950">{{ $election->title }}</h2>
        <p class="text-slate-500 mt-2">{{ $election->description }}</p>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div><p class="text-sm text-slate-500">Status</p><p class="font-semibold">{{ ucfirst($election->status) }}</p></div>
            <div><p class="text-sm text-slate-500">Ends</p><p class="font-semibold">{{ $election->ends_at?->format('M d, Y H:i') }}</p></div>
        </div>
    </div>

    @if ($election->status === 'active' && ! $hasVoted)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-navy-950 mb-4">Positions</h3>
            <div class="space-y-3">
                @foreach ($election->positions()->ordered()->get() as $position)
                    <a href="{{ route('voter.ballot.show', [$election, $position]) }}" class="block p-4 rounded-lg bg-slate-50 hover:bg-slate-100">
                        <p class="font-semibold text-navy-950">{{ $position->title }}</p>
                        <p class="text-sm text-slate-500">{{ $position->candidates()->approved()->count() }} candidates</p>
                    </a>
                @endforeach
            </div>
        </div>
    @elseif ($hasVoted)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <p class="text-green-700 font-semibold">You have voted in this election.</p>
            <a href="{{ route('voter.elections.results', $election) }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium mt-2 inline-block">View Results</a>
        </div>
    @endif
</div>
@endsection
