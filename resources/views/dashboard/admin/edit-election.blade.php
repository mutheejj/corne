@extends('layouts.dashboard')

@section('page-title', 'Edit Election')

@section("sidebar-nav")
    @include("partials.sidebar-admin")
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-navy-950">Edit Election</h2>

    <form action="{{ route('admin.elections.update', $election) }}" method="POST" class="bg-white rounded-xl p-6 border border-slate-200 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $election->title) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>{{ old('description', $election->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
            <select name="type" class="w-full px-4 py-2 border border-slate-300 rounded-lg" required>
                <option value="general" {{ $election->type === 'general' ? 'selected' : '' }}>General</option>
                <option value="faculty" {{ $election->type === 'faculty' ? 'selected' : '' }}>Faculty</option>
                <option value="department" {{ $election->type === 'department' ? 'selected' : '' }}>Department</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Starts At</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $election->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Ends At</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $election->ends_at?->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg" required>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_anonymous" {{ $election->is_anonymous ? 'checked' : '' }} class="rounded border-slate-300 text-orange-500">
                <span class="text-sm text-slate-700">Anonymous Voting</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="require_2fa" {{ $election->require_2fa ? 'checked' : '' }} class="rounded border-slate-300 text-orange-500">
                <span class="text-sm text-slate-700">Require 2FA</span>
            </label>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium">Update Election</button>
            <a href="{{ route('admin.elections.show', $election) }}" class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
