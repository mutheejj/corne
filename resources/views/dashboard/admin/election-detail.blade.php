@extends('layouts.dashboard')

@section('page-title', $election->title)

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-950">{{ $election->title }}</h2>
            <p class="text-slate-500 mt-1">{{ $election->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.elections.edit', $election) }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 text-sm font-medium">Edit</a>
            @if(in_array($election->status, ['draft', 'scheduled']))
                <form action="{{ route('admin.elections.start', $election) }}" method="POST">@csrf <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-medium">Start</button></form>
            @endif
            @if(in_array($election->status, ['active', 'paused']))
                <form action="{{ route('admin.elections.end', $election) }}" method="POST">@csrf <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-medium">End</button></form>
            @endif
            @if($election->status === 'completed' && ! $election->results_published_at)
                <form action="{{ route('admin.elections.publish-results', $election) }}" method="POST">@csrf <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm font-medium">Publish Results</button></form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <p class="text-sm text-slate-500">Status</p>
            <p class="text-lg font-bold text-navy-950 mt-1">{{ ucfirst($election->status) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <p class="text-sm text-slate-500">Positions</p>
            <p class="text-lg font-bold text-navy-950 mt-1">{{ $election->positions->count() }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <p class="text-sm text-slate-500">Voters</p>
            <p class="text-lg font-bold text-navy-950 mt-1">{{ $election->total_voters }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <p class="text-sm text-slate-500">Turnout</p>
            <p class="text-lg font-bold text-navy-950 mt-1">{{ $election->turnout_percentage }}%</p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-navy-950">Positions</h3>
            <button onclick="document.getElementById('add-position').classList.toggle('hidden')" class="text-orange-600 text-sm font-medium">+ Add Position</button>
        </div>

        <div id="add-position" class="hidden mb-4 p-4 bg-slate-50 rounded-lg">
            <form action="{{ route('admin.positions.store', $election) }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="election_id" value="{{ $election->id }}">
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="title" placeholder="Position Title" class="px-4 py-2 border border-slate-300 rounded-lg" required>
                    <input type="number" name="max_votes" placeholder="Max Votes" value="1" class="px-4 py-2 border border-slate-300 rounded-lg" required min="1">
                </div>
                <textarea name="description" placeholder="Description (optional)" rows="2" class="w-full px-4 py-2 border border-slate-300 rounded-lg"></textarea>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-medium">Add Position</button>
            </form>
        </div>

        <div class="space-y-3">
            @forelse ($election->positions as $position)
                <div class="p-4 border border-slate-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-navy-950">{{ $position->title }}</p>
                            <p class="text-sm text-slate-500">{{ $position->candidates->count() }} candidates | Max votes: {{ $position->max_votes }}</p>
                        </div>
                        <form action="{{ route('admin.positions.destroy', $position) }}" method="POST" onsubmit="return confirm('Delete this position?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-sm hover:text-red-700">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-slate-400 text-sm">No positions yet.</p>
            @endforelse
        </div>
    </div>

    @if($election->results_published_at)
        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-navy-950">Results</h3>
                <a href="{{ route('admin.elections.results', $election) }}" class="text-orange-600 text-sm font-medium">View Full Results</a>
            </div>
        </div>
    @endif
</div>
@endsection
