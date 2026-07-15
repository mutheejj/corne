@extends('layouts.dashboard')

@section('page-title', 'Campaign Profile')

@section('sidebar-nav')
    <a href="{{ route('candidate.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.dashboard') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        Dashboard
    </a>
    <a href="{{ route('candidate.profile') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.profile') || request()->routeIs('candidate.profile.update') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Campaign Profile
    </a>
    <a href="{{ route('candidate.election') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.election') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h8"/><path d="M4 6h16"/><path d="M4 18h16"/></svg>
        My Election
    </a>
    <a href="{{ route('candidate.position') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.position') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V8a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v12"/><path d="M8 20h8"/><path d="M12 6v14"/></svg>
        My Position
    </a>
    <a href="{{ route('candidate.results') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('candidate.results') ? 'bg-orange-500 text-white' : 'text-navy-300 hover:bg-navy-800' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M11 16V5"/><path d="M15 16v-3"/><path d="M19 16V8"/></svg>
        Results
    </a>
@endsection

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-bold text-navy-950 mb-6">Campaign Profile</h3>

        {{-- Photo --}}
        <div class="flex items-center gap-6 mb-6">
            <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->user->name }}" class="w-24 h-24 rounded-full object-cover">
            <form method="POST" action="{{ route('candidate.photo.upload') }}" enctype="multipart/form-data">
                @csrf
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Upload Photo</span>
                    <input type="file" name="photo" accept="image/*" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                </label>
                <button type="submit" class="mt-3 px-4 py-2 bg-navy-950 text-white rounded-lg text-sm font-medium hover:bg-navy-900">Upload</button>
            </form>
        </div>

        <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="manifesto_title" class="block text-sm font-semibold text-navy-950 mb-2">Manifesto Title</label>
                    <input type="text" id="manifesto_title" name="manifesto_title" value="{{ old('manifesto_title', $candidate->manifesto_title) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                </div>

                <div>
                    <label for="manifesto" class="block text-sm font-semibold text-navy-950 mb-2">Manifesto</label>
                    <textarea id="manifesto" name="manifesto" rows="8" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>{{ old('manifesto', $candidate->manifesto) }}</textarea>
                    <p class="text-xs text-slate-400 mt-1">Minimum 100 characters.</p>
                </div>

                <div>
                    <label for="slogan" class="block text-sm font-semibold text-navy-950 mb-2">Slogan</label>
                    <input type="text" id="slogan" name="slogan" value="{{ old('slogan', $candidate->slogan) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <button type="submit" class="px-6 py-2.5 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
