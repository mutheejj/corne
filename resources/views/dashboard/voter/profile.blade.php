@extends('layouts.dashboard')

@section('page-title', 'My Profile')

@section('sidebar-nav')
    @include('partials.sidebar-voter')
@endsection

@section('content')
<div class="max-w-4xl space-y-6">
    {{-- Profile Header Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="h-28 gradient-navy relative">
            <div class="absolute inset-0 grid-pattern opacity-50"></div>
        </div>
        <div class="px-6 pb-6 -mt-12">
            <div class="flex items-end gap-5">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl ring-4 ring-white shadow-lg bg-white">
                <div class="flex-1 pb-2">
                    <h2 class="text-2xl font-bold text-navy-950">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="badge badge-orange">{{ ucfirst($user->role) }}</span>
                        @if ($user->hasVerifiedEmail())
                            <span class="badge badge-navy"><i data-lucide="badge-check" class="w-3 h-3"></i> Verified</span>
                        @else
                            <span class="badge badge-navy"><i data-lucide="alert-circle" class="w-3 h-3"></i> Unverified</span>
                        @endif
                        @if ($user->is_active)
                            <span class="badge badge-navy"><i data-lucide="check-circle" class="w-3 h-3"></i> Active</span>
                        @else
                            <span class="badge badge-navy"><i data-lucide="x-circle" class="w-3 h-3"></i> Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Student Details --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <button type="button" onclick="toggleSection('student-details')" class="w-full flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <i data-lucide="graduation-cap" class="w-5 h-5 text-orange-600"></i>
                </div>
                <h3 class="text-lg font-bold text-navy-950">Student Details</h3>
            </div>
            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" id="student-details-icon"></i>
        </button>
        <div id="student-details" class="border-t border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-slate-200">
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="id-card" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Student ID</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->student_id ?? '—' }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="building-2" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Faculty</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->faculty ?? '—' }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="folder" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Department</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->department ?? '—' }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="book-open" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Course</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->course ?? '—' }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="layers" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Year of Study</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->year_of_study ? 'Year ' . $user->year_of_study : '—' }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Phone</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->phone ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Account Information --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <button type="button" onclick="toggleSection('account-info')" class="w-full flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-navy-50 flex items-center justify-center">
                    <i data-lucide="info" class="w-5 h-5 text-navy-600"></i>
                </div>
                <h3 class="text-lg font-bold text-navy-950">Account Information</h3>
            </div>
            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" id="account-info-icon"></i>
        </button>
        <div id="account-info" class="border-t border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-slate-200">
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Email</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->email }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="shield-check" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Email Verified</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->email_verified_at?->format('M d, Y H:i') ?? 'Not verified' }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Joined</p>
                    </div>
                    <p class="text-sm font-medium text-navy-950">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div class="bg-white p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="user-check" class="w-4 h-4 text-slate-400"></i>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Account Status</p>
                    </div>
                    <p class="text-sm font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <button type="button" onclick="toggleSection('edit-profile')" class="w-full flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <i data-lucide="edit-3" class="w-5 h-5 text-orange-600"></i>
                </div>
                <h3 class="text-lg font-bold text-navy-950">Edit Profile</h3>
            </div>
            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300" id="edit-profile-icon"></i>
        </button>
        <div id="edit-profile" class="border-t border-slate-200 p-6 hidden">
            <form method="POST" action="{{ route('voter.profile.update') }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">Faculty</label>
                        <input type="text" name="faculty" value="{{ old('faculty', $user->faculty) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">Department</label>
                        <input type="text" name="department" value="{{ old('department', $user->department) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">Course</label>
                        <input type="text" name="course" value="{{ old('course', $user->course) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">Year of Study</label>
                        <input type="number" name="year_of_study" value="{{ old('year_of_study', $user->year_of_study) }}" min="1" max="6" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-navy-950 mb-2">New Password (optional)</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/20 hover:-translate-y-0.5">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleSection(id) {
        var section = document.getElementById(id);
        var icon = document.getElementById(id + '-icon');
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
            section.classList.add('hidden');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
    }
</script>
@endpush
@endsection
