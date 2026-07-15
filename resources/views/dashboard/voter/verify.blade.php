@extends('layouts.dashboard')

@section('page-title', 'Verify Vote')

@section('content')
<div class="max-w-lg mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-xl font-bold text-navy-950 mb-4">Verify Your Vote</h2>
        <form method="POST" action="{{ route('voter.verify-vote.result') }}">
            @csrf
            <label class="block">
                <span class="text-sm font-semibold text-navy-950">Enter Verification Code</span>
                <input type="text" name="verification_code" class="mt-2 w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-mono" placeholder="XXXXXXXXXXXXXXXX" required>
            </label>
            <button type="submit" class="mt-4 px-6 py-2.5 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600">Verify</button>
        </form>
    </div>

    @isset($result)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <p class="font-semibold text-green-700 mb-2">Vote Verified!</p>
            <div class="space-y-1 text-sm">
                <p><span class="text-slate-500">Election:</span> {{ $result['election_title'] }}</p>
                <p><span class="text-slate-500">Position:</span> {{ $result['position_title'] }}</p>
                <p><span class="text-slate-500">Voted At:</span> {{ $result['cast_at']->format('M d, Y H:i') }}</p>
                <p><span class="text-slate-500">Receipt Hash:</span> <span class="font-mono text-xs">{{ substr($result['receipt_hash'], 0, 32) }}...</span></p>
                <p class="text-green-600 font-medium mt-2">Your vote has been counted.</p>
            </div>
        </div>
    @endisset
</div>
@endsection
