@extends('layouts.dashboard')

@section('page-title', 'Candidates')

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-navy-950">Candidates</h2>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Name</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Position</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Election</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($candidates as $candidate)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-navy-950">{{ $candidate->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $candidate->position->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $candidate->election->title }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($candidate->status === 'approved') bg-green-100 text-green-700
                                @elseif($candidate->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($candidate->status === 'rejected') bg-red-100 text-red-700
                                @else bg-slate-100 text-slate-700 @endif">{{ ucfirst($candidate->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($candidate->status === 'pending')
                                <form action="{{ route('admin.candidates.approve', $candidate) }}" method="POST" class="inline">@csrf <button class="text-green-600 hover:text-green-700 text-sm font-medium">Approve</button></form>
                                <button onclick="document.getElementById('reject-{{ $candidate->id }}').classList.toggle('hidden')" class="text-red-600 hover:text-red-700 text-sm font-medium ml-2">Reject</button>
                                <form id="reject-{{ $candidate->id }}" action="{{ route('admin.candidates.reject', $candidate) }}" method="POST" class="hidden mt-2">
                                    @csrf
                                    <textarea name="rejection_reason" placeholder="Reason for rejection" class="w-full px-3 py-1.5 text-sm border border-slate-300 rounded" required></textarea>
                                    <button type="submit" class="mt-1 text-red-600 text-sm">Confirm Reject</button>
                                </form>
                            @elseif($candidate->status === 'approved')
                                <button onclick="document.getElementById('disqualify-{{ $candidate->id }}').classList.toggle('hidden')" class="text-red-600 hover:text-red-700 text-sm font-medium">Disqualify</button>
                                <form id="disqualify-{{ $candidate->id }}" action="{{ route('admin.candidates.disqualify', $candidate) }}" method="POST" class="hidden mt-2">
                                    @csrf
                                    <textarea name="rejection_reason" placeholder="Reason for disqualification" class="w-full px-3 py-1.5 text-sm border border-slate-300 rounded" required></textarea>
                                    <button type="submit" class="mt-1 text-red-600 text-sm">Confirm Disqualify</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No candidates found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $candidates->links() }}
</div>
@endsection
