@extends('layouts.dashboard')

@section('page-title', 'Withdraw Candidacy')

@section("sidebar-nav")
    @include("partials.sidebar-candidate")
@endsection

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h2 class="text-2xl font-bold text-navy-950 text-center">Withdraw Candidacy</h2>
        <p class="text-slate-500 text-center mt-2">Are you sure you want to withdraw your candidacy? This action cannot be undone.</p>

        <form method="POST" action="{{ route('candidate.withdraw') }}">
            @csrf
            <label class="flex items-center gap-3 mt-6 p-4 bg-red-50 rounded-lg cursor-pointer">
                <input type="checkbox" name="confirm" value="1" required class="w-5 h-5 text-red-600 focus:ring-red-500">
                <span class="text-sm text-red-700">I understand this action is permanent and cannot be undone.</span>
            </label>
            <div class="mt-6 flex justify-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">Withdraw Candidacy</button>
                <a href="{{ route('candidate.dashboard') }}" class="px-6 py-2.5 text-slate-500 hover:text-slate-700 font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
