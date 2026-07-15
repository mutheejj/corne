@extends('layouts.dashboard')

@section('page-title', 'Election Results')

@section('sidebar-nav')
    @include('partials.sidebar-voter')
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-navy-950">{{ $election->title }}</h2>
            <div class="text-right">
                <p class="text-sm text-slate-500">Turnout</p>
                <p class="text-xl font-bold text-orange-500">{{ $turnout['turnout_percentage'] }}%</p>
            </div>
        </div>
    </div>

    @foreach ($results['positions'] as $positionResult)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-navy-950 mb-1">{{ $positionResult['position']['title'] }}</h3>
            <p class="text-sm text-slate-500 mb-4">Total Votes: {{ $positionResult['total_votes'] }}</p>
            <div style="height: 200px;" class="mb-4">
                <canvas id="chart-{{ $positionResult['position']['id'] }}"></canvas>
            </div>
            <div class="space-y-4">
                @foreach ($positionResult['candidates'] as $c)
                    <div class="flex items-center gap-4">
                        <span class="w-8 text-center font-bold text-slate-400">{{ $c['rank'] }}</span>
                        <img src="{{ $c['candidate']->photo_url }}" alt="{{ $c['candidate']->user->name }}" class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-navy-950">{{ $c['candidate']->user->name }}</p>
                                <span class="text-sm text-slate-600">{{ $c['vote_count'] }} votes ({{ $c['vote_percentage'] }}%)</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-orange-500 rounded-full" style="width: {{ $c['vote_percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($positionResult['candidates']->isEmpty())
                    <p class="text-slate-400 text-sm">No votes cast for this position.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    @foreach ($results['positions'] as $positionResult)
        new Chart(document.getElementById('chart-{{ $positionResult['position']['id'] }}'), {
            type: 'bar',
            data: {
                labels: [@foreach ($positionResult['candidates'] as $c)'{{ $c['candidate']->user->name }}'@if(!$loop->last), @endif @endforeach],
                datasets: [{
                    label: 'Votes',
                    data: [@foreach ($positionResult['candidates'] as $c){{ $c['vote_count'] }}@if(!$loop->last), @endif @endforeach],
                    backgroundColor: '#f97316',
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });
    @endforeach
</script>
@endpush
@endsection
