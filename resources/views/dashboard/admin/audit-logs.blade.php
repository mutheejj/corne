@extends('layouts.dashboard')

@section('page-title', 'Audit Logs')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Dashboard</a>
    <a href="{{ route('admin.elections.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Elections</a>
    <a href="{{ route('admin.candidates.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Candidates</a>
    <a href="{{ route('admin.voters.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Voters</a>
    <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-orange-500 text-white">Audit Logs</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">User</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Action</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Description</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">IP</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse ($logs as $log)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm text-navy-950">{{ $log->user?->name ?? 'System' }}</td>
                    <td class="px-6 py-4 text-sm"><span class="px-2 py-1 bg-slate-100 rounded text-xs font-medium">{{ $log->action }}</span></td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $log->description }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $log->ip_address }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $log->created_at->format('M d, Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No audit logs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $logs->links() }}
@endsection
