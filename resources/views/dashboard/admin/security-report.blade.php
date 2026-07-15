@extends('layouts.dashboard')

@section('page-title', 'Security Report')

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-navy-950">Security Report</h2>

    {{-- Overall Status --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-navy-950 mb-4">Overall Security Status</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">Encryption</p>
                <p class="text-lg font-bold {{ $report['encryption_enabled'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $report['encryption_enabled'] ? 'Enabled' : 'Disabled' }}
                </p>
            </div>
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">Vote Integrity</p>
                <p class="text-lg font-bold {{ $report['vote_integrity'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $report['vote_integrity'] ? 'OK' : 'Mismatch' }}
                </p>
            </div>
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">Active Elections</p>
                <p class="text-lg font-bold text-navy-950">{{ $report['active_elections'] }}</p>
            </div>
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">Failed Logins (7d)</p>
                <p class="text-lg font-bold {{ $report['failed_logins_7d'] > 10 ? 'text-orange-600' : 'text-green-600' }}">
                    {{ $report['failed_logins_7d'] }}
                </p>
            </div>
        </div>
    </div>

    {{-- Vote Integrity --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-navy-950 mb-4">Vote Integrity Check</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">Total Votes</p>
                <p class="text-lg font-bold text-navy-950">{{ $report['total_votes'] }}</p>
            </div>
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">Vote Records</p>
                <p class="text-lg font-bold text-navy-950">{{ $report['total_vote_records'] }}</p>
            </div>
            <div class="p-4 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">Integrity Match</p>
                <p class="text-lg font-bold {{ $report['vote_integrity'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $report['vote_integrity'] ? 'Pass' : 'Fail' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Security Headers --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-navy-950 mb-4">Security Headers Status</h3>
        <ul class="space-y-2">
            <li class="flex items-center gap-2 text-sm">
                <span class="text-green-600">&#10003;</span> X-Content-Type-Options: nosniff
            </li>
            <li class="flex items-center gap-2 text-sm">
                <span class="text-green-600">&#10003;</span> X-Frame-Options: DENY
            </li>
            <li class="flex items-center gap-2 text-sm">
                <span class="text-green-600">&#10003;</span> X-XSS-Protection: 1; mode=block
            </li>
            <li class="flex items-center gap-2 text-sm">
                <span class="text-green-600">&#10003;</span> Referrer-Policy: strict-origin-when-cross-origin
            </li>
            <li class="flex items-center gap-2 text-sm">
                <span class="text-green-600">&#10003;</span> Content-Security-Policy: configured
            </li>
        </ul>
    </div>
</div>
@endsection
