@extends('layouts.dashboard')

@section('page-title', 'Notifications')

@section('content')
<div class="max-w-3xl space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-950">Notifications</h2>
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit" class="text-sm text-orange-600 hover:text-orange-700 font-medium">Mark all as read</button>
        </form>
    </div>

    @forelse ($notifications as $notification)
        <div class="bg-white rounded-xl border border-slate-200 p-4 {{ $notification->read_at ? '' : 'border-l-4 border-l-orange-500' }}">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold text-navy-950">{{ $notification->title }}</p>
                    <p class="text-sm text-slate-600 mt-1">{{ $notification->message }}</p>
                    <p class="text-xs text-slate-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if (! $notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button type="submit" class="text-xs text-slate-400 hover:text-orange-600">Mark read</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-400">No notifications.</div>
    @endforelse

    {{ $notifications->links() }}
</div>
@endsection
