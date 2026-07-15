@extends('layouts.dashboard')

@section('page-title', 'Vote History')

@section('sidebar-nav')
    @include('partials.sidebar-voter')
@endsection

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-navy-950">Vote History</h2>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Election</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Position</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Verification Code</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Voted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($voteRecords as $record)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-navy-950">{{ $record->election->title }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $record->position->title }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-orange-600">{{ $record->verification_code }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $record->voted_at->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">No vote records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $voteRecords->links() }}
</div>
@endsection
