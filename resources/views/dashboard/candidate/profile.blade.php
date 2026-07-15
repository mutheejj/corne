@extends('layouts.dashboard')

@section('page-title', 'Campaign Profile')

@section("sidebar-nav")
    @include("partials.sidebar-candidate")
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
