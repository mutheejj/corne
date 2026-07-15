@extends('layouts.dashboard')

@section('page-title', 'My Profile')

@section('sidebar-nav')
    <a href="{{ route('voter.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Dashboard</a>
    <a href="{{ route('voter.elections.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Elections</a>
    <a href="{{ route('voter.vote-history') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-300 hover:bg-navy-800">Vote History</a>
    <a href="{{ route('voter.profile') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-orange-500 text-white">Profile</a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-navy-950 mb-6">Profile</h3>
        <form method="POST" action="{{ route('voter.profile.update') }}">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-navy-950 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy-950 mb-2">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy-950 mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy-950 mb-2">New Password (optional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
