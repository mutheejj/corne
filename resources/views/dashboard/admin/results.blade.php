@extends('layouts.dashboard')

@section('page-title', 'Election Results')

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="space-y-6">
    {{-- Election Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-navy-950">{{ $election->title }}</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Status: <span class="font-medium">{{ ucfirst($election->status) }}</span> |
                    {{ $election->starts_at->format('M d, Y') }} - {{ $election->ends_at->format('M d, Y') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-slate-500">Turnout</p>
                <p class="text-2xl font-bold text-orange-500">{{ $turnout['turnout_percentage'] }}%</p>
                <p class="text-xs text-slate-400">{{ $turnout['voted'] }} / {{ $turnout['total_voters'] }} voted</p>
            </div>
        </div>
        <div class="mt-4 flex gap-3">
            <a href="{{ route('admin.elections.results.csv', $election) }}" class="px-4 py-2 bg-navy-900 text-white rounded-lg text-sm font-medium hover:bg-navy-800">Download CSV</a>
            <a href="{{ route('admin.elections.results.pdf', $election) }}" target="_blank" class="px-4 py-2 bg-navy-900 text-white rounded-lg text-sm font-medium hover:bg-navy-800">Print / PDF</a>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-300">Print</button>
            @if ($liveResultsEnabled)
                <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Live
                </span>
            @endif
        </div>
    </div>

    {{-- Turnout Doughnut Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-navy-950 mb-4">Voter Turnout</h3>
        <div class="flex items-center gap-8">
            <div style="width: 200px; height: 200px;">
                <canvas id="turnoutChart"></canvas>
            </div>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-orange-500 rounded"></span>
                    <span class="text-sm">Voted: {{ $turnout['voted'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 bg-slate-200 rounded"></span>
                    <span class="text-sm">Not Voted: {{ $turnout['not_voted'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Position Results --}}
    @foreach ($results['positions'] as $positionResult)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-navy-950">{{ $positionResult['position']['title'] }}</h3>
                    <p class="text-sm text-slate-500">Total Votes: {{ $positionResult['total_votes'] }} | Abstentions: {{ $positionResult['abstentions'] }}</p>
                </div>
                @if ($positionResult['is_tie'])
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Tie</span>
                @endif
            </div>
            <div style="height: 200px;" class="mb-4">
                <canvas id="chart-{{ $positionResult['position']['id'] }}"></canvas>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-2 text-slate-500 font-medium">Rank</th>
                        <th class="text-left py-2 text-slate-500 font-medium">Candidate</th>
                        <th class="text-right py-2 text-slate-500 font-medium">Votes</th>
                        <th class="text-right py-2 text-slate-500 font-medium">Percentage</th>
                        <th class="text-center py-2 text-slate-500 font-medium">Winner</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($positionResult['candidates'] as $c)
                        <tr class="border-b border-slate-100">
                            <td class="py-3 font-bold text-slate-400">{{ $c['rank'] }}</td>
                            <td class="py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $c['candidate']->photo_url }}" alt="{{ $c['candidate']->user->name }}" class="w-8 h-8 rounded-full">
                                    <span class="font-medium text-navy-950">{{ $c['candidate']->user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-right font-medium">{{ $c['vote_count'] }}</td>
                            <td class="py-3 text-right">{{ $c['vote_percentage'] }}%</td>
                            <td class="py-3 text-center">
                                @if ($c['is_winner'])
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Winner</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($positionResult['candidates']->isEmpty())
                        <tr><td colspan="5" class="py-4 text-center text-slate-400">No votes cast for this position.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endforeach

    {{-- Vote Timeline --}}
    @if (!empty($timeline))
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-navy-950 mb-4">Vote Timeline</h3>
        <div style="height: 250px;">
            <canvas id="timelineChart"></canvas>
        </div>
    </div>
    @endif

    {{-- Audit Report --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <button onclick="document.getElementById('audit-report').classList.toggle('hidden')" class="flex items-center justify-between w-full">
            <h3 class="text-lg font-bold text-navy-950">Audit Report</h3>
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
        <div id="audit-report" class="hidden mt-4 space-y-3">
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-sm text-slate-500">Total Votes</p>
                    <p class="text-xl font-bold text-navy-950">{{ $auditReport['total_votes'] }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-sm text-slate-500">Vote Records</p>
                    <p class="text-xl font-bold text-navy-950">{{ $auditReport['total_vote_records'] }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <p class="text-sm text-slate-500">Integrity Check</p>
                    <p class="text-xl font-bold {{ $auditReport['integrity_check'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ $auditReport['integrity_check'] ? 'Passed' : 'Failed' }}
                    </p>
                </div>
            </div>
            @if ($auditReport['discrepancies'])
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 text-sm">
                    Warning: Vote count ({{ $auditReport['total_votes'] }}) does not match vote record count ({{ $auditReport['total_vote_records'] }}).
                </div>
            @endif
            <div class="max-h-64 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-2 text-slate-500">Action</th>
                            <th class="text-left py-2 text-slate-500">Description</th>
                            <th class="text-left py-2 text-slate-500">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($auditReport['audit_logs'] as $log)
                            <tr class="border-b border-slate-100">
                                <td class="py-2 font-mono text-xs">{{ $log->action }}</td>
                                <td class="py-2">{{ $log->description }}</td>
                                <td class="py-2 text-slate-400">{{ $log->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    // Turnout doughnut chart
    new Chart(document.getElementById('turnoutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Voted', 'Not Voted'],
            datasets: [{
                data: [{{ $turnout['voted'] }}, {{ $turnout['not_voted'] }}],
                backgroundColor: ['#f97316', '#e2e8f0'],
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Position bar charts
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

    // Timeline chart
    @if (!empty($timeline))
    new Chart(document.getElementById('timelineChart'), {
        type: 'line',
        data: {
            labels: [@foreach ($timeline as $point)'{{ $point['timestamp'] }}'@if(!$loop->last), @endif @endforeach],
            datasets: [{
                label: 'Votes',
                data: [@foreach ($timeline as $point){{ $point['votes'] }}@if(!$loop->last), @endif @endforeach],
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                fill: true,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });
    @endif

    // Live results polling
    @if ($liveResultsEnabled)
    setInterval(function() {
        fetch('{{ route('admin.elections.results.live', $election) }}')
            .then(r => r.json())
            .then(data => { location.reload(); })
            .catch(() => {});
    }, 30000);
    @endif
</script>
@endpush
@endsection
