@extends('layouts.dashboard')

@section('page-title', 'Elections')

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-950">Elections</h2>
        <a href="{{ route('admin.elections.create') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">Create Election</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Title</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Type</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Starts</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Ends</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($elections as $election)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.elections.show', $election) }}" class="font-medium text-navy-950 hover:text-orange-600">{{ $election->title }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($election->status === 'active') bg-green-100 text-green-700
                                @elseif($election->status === 'completed') bg-blue-100 text-blue-700
                                @elseif($election->status === 'draft') bg-slate-100 text-slate-700
                                @elseif($election->status === 'scheduled') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">{{ ucfirst($election->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ ucfirst($election->type) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $election->starts_at?->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $election->ends_at?->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.elections.show', $election) }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No elections found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $elections->links() }}
</div>
@endsection
