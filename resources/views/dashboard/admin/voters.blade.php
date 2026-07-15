@extends('layouts.dashboard')

@section('page-title', 'Voters')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-navy-300 hover:bg-navy-800 text-sm font-medium">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
    </a>
    <a href="{{ route('admin.elections.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-navy-300 hover:bg-navy-800 text-sm font-medium">
        <i data-lucide="vote" class="w-5 h-5"></i> Elections
    </a>
    <a href="{{ route('admin.candidates.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-navy-300 hover:bg-navy-800 text-sm font-medium">
        <i data-lucide="users" class="w-5 h-5"></i> Candidates
    </a>
    <a href="{{ route('admin.voters.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-orange-500 text-white text-sm font-medium">
        <i data-lucide="user-check" class="w-5 h-5"></i> Voters
    </a>
    <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-navy-300 hover:bg-navy-800 text-sm font-medium">
        <i data-lucide="scroll-text" class="w-5 h-5"></i> Audit Logs
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-navy-950">Voters</h2>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Name</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Student ID</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Email</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Faculty</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Elections</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($voters as $voter)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-navy-950">{{ $voter->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $voter->student_id ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $voter->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $voter->faculty ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $voter->elections->count() }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $voter->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $voter->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No voters found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $voters->links() }}
</div>
@endsection
