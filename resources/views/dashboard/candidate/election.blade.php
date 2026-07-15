@extends('layouts.dashboard')

@section('page-title', 'My Election')

@section("sidebar-nav")
    @include("partials.sidebar-candidate")
@endsection

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-2xl font-bold text-navy-950">{{ $election->title }}</h3>
        <p class="text-slate-500 mt-2">{{ $election->description }}</p>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
                <p class="text-sm text-slate-500">Status</p>
                <p class="font-semibold text-navy-950">{{ ucfirst($election->status) }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Type</p>
                <p class="font-semibold text-navy-950">{{ ucfirst($election->type) }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Starts At</p>
                <p class="font-semibold text-navy-950">{{ $election->starts_at?->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Ends At</p>
                <p class="font-semibold text-navy-950">{{ $election->ends_at?->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-bold text-navy-950 mb-4">Positions</h4>
        <div class="space-y-3">
            @foreach ($election->positions()->ordered()->get() as $position)
                <div class="flex items-center justify-between p-4 rounded-lg bg-slate-50">
                    <div>
                        <p class="font-semibold text-navy-950">{{ $position->title }}</p>
                        <p class="text-sm text-slate-500">{{ $position->candidates()->count() }} candidates</p>
                    </div>
                    @if ($position->id === $candidate->position_id)
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">Your Position</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
