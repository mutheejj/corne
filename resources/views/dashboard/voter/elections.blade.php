@extends('layouts.dashboard')

@section('page-title', 'Elections')

@section('sidebar-nav')
    <a href="{{ route('voter.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Dashboard</a>
    <a href="{{ route('voter.elections.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-orange-500 text-white">Elections</a>
    <a href="{{ route('voter.vote-history') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Vote History</a>
    <a href="{{ route('voter.profile') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Profile</a>
@endsection

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-navy-950">Elections</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($elections as $election)
            <a href="{{ route('voter.elections.show', $election) }}" class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-bold text-navy-950">{{ $election->title }}</h3>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $election->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $election->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $election->status === 'scheduled' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    ">{{ ucfirst($election->status) }}</span>
                </div>
                <p class="text-sm text-slate-500">{{ $election->starts_at?->format('M d, Y') }} — {{ $election->ends_at?->format('M d, Y') }}</p>
            </a>
        @empty
            <p class="text-slate-400">You are not registered for any elections.</p>
        @endforelse
    </div>
    {{ $elections->links() }}
</div>
@endsection
