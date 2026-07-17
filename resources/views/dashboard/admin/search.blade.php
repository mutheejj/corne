@extends('layouts.dashboard')

@section('page-title', 'Search Results')

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="mb-6">
    <h2 class="text-lg font-bold text-navy-950">Search results for "{{ $query }}"</h2>
    <p class="text-sm text-slate-500 mt-1">Found {{ $elections->count() + $candidates->count() + $voters->count() + $logs->count() }} result(s)</p>
</div>

{{-- Elections --}}
@if ($elections->isNotEmpty())
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-navy-950">Elections ({{ $elections->count() }})</h3>
        </div>
        <table class="w-full">
            <tbody class="divide-y divide-slate-200">
                @foreach ($elections as $election)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-navy-950">{{ $election->name }}</td>
                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 bg-slate-100 rounded text-xs font-medium">{{ $election->status }}</span></td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.elections.show', $election) }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- Candidates --}}
@if ($candidates->isNotEmpty())
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-navy-950">Candidates ({{ $candidates->count() }})</h3>
        </div>
        <table class="w-full">
            <tbody class="divide-y divide-slate-200">
                @foreach ($candidates as $candidate)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-navy-950">{{ $candidate->user?->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $candidate->position?->title }}</td>
                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 bg-slate-100 rounded text-xs font-medium">{{ $candidate->status }}</span></td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.candidates.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- Voters --}}
@if ($voters->isNotEmpty())
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-navy-950">Voters ({{ $voters->count() }})</h3>
        </div>
        <table class="w-full">
            <tbody class="divide-y divide-slate-200">
                @foreach ($voters as $voter)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-navy-950">{{ $voter->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $voter->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $voter->student_id }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.voters.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

{{-- Audit Logs --}}
@if ($logs->isNotEmpty())
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-navy-950">Audit Logs ({{ $logs->count() }})</h3>
        </div>
        <table class="w-full">
            <tbody class="divide-y divide-slate-200">
                @foreach ($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-navy-950">{{ $log->user?->name ?? 'System' }}</td>
                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 bg-slate-100 rounded text-xs font-medium">{{ $log->action }}</span></td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $log->description }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.audit-logs.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@if ($elections->isEmpty() && $candidates->isEmpty() && $voters->isEmpty() && $logs->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
        <p class="text-slate-400">No results found for "{{ $query }}".</p>
    </div>
@endif
@endsection
