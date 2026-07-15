@extends('layouts.dashboard')

@section('page-title', 'Election Results')

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
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-navy-950">{{ $positionResults['position']['title'] }}</h3>
                <p class="text-slate-500 mt-1">{{ $election->title }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-slate-500">Your Rank</p>
                <p class="text-3xl font-bold text-orange-500">#{{ $candidateResult['rank'] }}</p>
                @if ($candidateResult['is_winner'])
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Winner</span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-bold text-navy-950 mb-4">Your Performance</h4>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-slate-50 rounded-lg p-4 text-center">
                <p class="text-sm text-slate-500">Votes</p>
                <p class="text-2xl font-bold text-navy-950">{{ $candidateResult['vote_count'] }}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-4 text-center">
                <p class="text-sm text-slate-500">Percentage</p>
                <p class="text-2xl font-bold text-navy-950">{{ $candidateResult['vote_percentage'] }}%</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-4 text-center">
                <p class="text-sm text-slate-500">Total Votes</p>
                <p class="text-2xl font-bold text-navy-950">{{ $positionResults['total_votes'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-bold text-navy-950 mb-4">Full Results</h4>
        <div style="height: 200px;" class="mb-4">
            <canvas id="candidateChart"></canvas>
        </div>
        <div class="space-y-4">
            @foreach ($positionResults['candidates'] as $c)
                <div class="flex items-center gap-4">
                    <span class="w-8 text-center font-bold text-slate-400">{{ $c['rank'] }}</span>
                    <img src="{{ $c['candidate']->photo_url }}" alt="{{ $c['candidate']->user->name }}" class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-semibold text-navy-950 {{ $c['candidate']->id === $candidate->id ? 'text-orange-600' : '' }}">
                                {{ $c['candidate']->user->name }} {{ $c['candidate']->id === $candidate->id ? '(You)' : '' }}
                            </p>
                            <span class="text-sm font-medium text-slate-600">{{ $c['vote_count'] }} votes ({{ $c['vote_percentage'] }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $c['candidate']->id === $candidate->id ? 'bg-orange-500' : 'bg-navy-300' }}" style="width: {{ $c['vote_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    new Chart(document.getElementById('candidateChart'), {
        type: 'bar',
        data: {
            labels: [@foreach ($positionResults['candidates'] as $c)'{{ $c['candidate']->user->name }}'@if(!$loop->last), @endif @endforeach],
            datasets: [{
                label: 'Votes',
                data: [@foreach ($positionResults['candidates'] as $c){{ $c['vote_count'] }}@if(!$loop->last), @endif @endforeach],
                backgroundColor: [@foreach ($positionResults['candidates'] as $c)'{{ $c["candidate"]->id === $candidate->id ? "#f97316" : "#9fb3c8" }}'@if(!$loop->last), @endif @endforeach],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush
@endsection
