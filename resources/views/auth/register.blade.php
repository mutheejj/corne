@extends('layouts.auth')

@section('title', 'Register as Voter — Cornelect')

@section('auth-content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-navy-950 mb-2">Create Your Account</h1>
        <p class="text-slate-500">Register as a voter to participate in university elections</p>
    </div>

    {{-- Registration Type Tabs --}}
    <div class="flex border-b border-slate-200 mb-6" data-tab-group>
        <div class="flex-1 auth-tab active" data-tab="voter">Voter Registration</div>
        <a href="{{ route('register.candidate') }}" class="flex-1 auth-tab">Candidate Registration</a>
    </div>

    <div data-tab-group>
        <div data-tab-panel="voter">
            <form method="POST" action="{{ route('register.post') }}" data-validate class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-navy-950 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                </div>

                <div>
                    <label for="student_id" class="block text-sm font-semibold text-navy-950 mb-2">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-input font-mono" placeholder="ABC123-1234/2023" value="{{ old('student_id') }}" required>
                    <p class="text-slate-400 text-xs mt-1">Format: XX###-####/####</p>
                    @error('student_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-navy-950 mb-2">University Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="you@university.ac.ke" value="{{ old('email') }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-navy-950 mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-input" placeholder="+254 7XX XXX XXX" value="{{ old('phone') }}" required>
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="faculty" class="block text-sm font-semibold text-navy-950 mb-2">Faculty</label>
                        <select id="faculty" name="faculty" class="form-input" required>
                            <option value="">Select faculty</option>
                            <option {{ old('faculty') == 'Computing & Information Technology' ? 'selected' : '' }}>Computing & Information Technology</option>
                            <option {{ old('faculty') == 'Engineering & Technology' ? 'selected' : '' }}>Engineering & Technology</option>
                            <option {{ old('faculty') == 'Science' ? 'selected' : '' }}>Science</option>
                            <option {{ old('faculty') == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                            <option {{ old('faculty') == 'Business' ? 'selected' : '' }}>Business</option>
                            <option {{ old('faculty') == 'Economics' ? 'selected' : '' }}>Economics</option>
                            <option {{ old('faculty') == 'Education' ? 'selected' : '' }}>Education</option>
                            <option {{ old('faculty') == 'Medical Sciences' ? 'selected' : '' }}>Medical Sciences</option>
                            <option {{ old('faculty') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                            <option {{ old('faculty') == 'Health Sciences' ? 'selected' : '' }}>Health Sciences</option>
                            <option {{ old('faculty') == 'Architecture & Building Sciences' ? 'selected' : '' }}>Architecture & Building Sciences</option>
                            <option {{ old('faculty') == 'Art & Design' ? 'selected' : '' }}>Art & Design</option>
                        </select>
                        @error('faculty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                    </div>
                    <div>
                        <label for="year_of_study" class="block text-sm font-semibold text-navy-950 mb-2">Year of Study</label>
                        <select id="year_of_study" name="year_of_study" class="form-input" required>
                            <option value="">Select year</option>
                            <option value="1" {{ old('year_of_study') == '1' ? 'selected' : '' }}>Year 1</option>
                            <option value="2" {{ old('year_of_study') == '2' ? 'selected' : '' }}>Year 2</option>
                            <option value="3" {{ old('year_of_study') == '3' ? 'selected' : '' }}>Year 3</option>
                            <option value="4" {{ old('year_of_study') == '4' ? 'selected' : '' }}>Year 4</option>
                            <option value="5" {{ old('year_of_study') == '5' ? 'selected' : '' }}>Year 5</option>
                            <option value="6" {{ old('year_of_study') == '6' ? 'selected' : '' }}>Year 6</option>
                        </select>
                        @error('year_of_study') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="department" class="block text-sm font-semibold text-navy-950 mb-2">Department</label>
                        <input type="text" id="department" name="department" class="form-input" placeholder="e.g. Computer Science" value="{{ old('department') }}" required>
                        @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                    </div>
                    <div>
                        <label for="course" class="block text-sm font-semibold text-navy-950 mb-2">Course</label>
                        <input type="text" id="course" name="course" class="form-input" placeholder="e.g. BSc Computer Science" value="{{ old('course') }}" required>
                        @error('course') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-navy-950 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="form-input pr-12" placeholder="Minimum 8 characters" required minlength="8">
                        <button type="button" data-password-toggle data-target="#password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500">
                            <svg data-password-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <div id="password-strength-bar" class="password-strength mt-2" style="width: 0;"></div>
                    <p id="password-strength-text" class="text-xs text-slate-400 mt-1"></p>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-navy-950 mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Re-enter password" required>
                    @error('password_confirmation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
                </div>

                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="terms" class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500 mt-1" required>
                    <span class="text-sm text-slate-600">I agree to the <a href="{{ route('terms') }}" class="text-orange-600 hover:text-orange-700 font-medium">Terms of Service</a> and <a href="{{ route('privacy') }}" class="text-orange-600 hover:text-orange-700 font-medium">Privacy Policy</a></span>
                </label>
                @error('terms') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                <button type="submit" class="btn-primary w-full justify-center">
                    Create Account
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </form>
        </div>
    </div>

    <p class="text-center text-sm text-slate-600 mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-semibold">Sign In</a>
    </p>
@endsection

@section('auth-sidebar')
    <div class="animate-fade-in-right">
        <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">
            Join the <span class="gradient-text">Democracy</span>
        </h2>
        <p class="text-white/70 text-lg mb-8 leading-relaxed">
            Create your voter account to participate in secure, transparent university elections. Your voice matters.
        </p>

        <div class="space-y-6">
            <div class="glass rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg gradient-orange flex items-center justify-center text-white font-bold">1</div>
                    <h4 class="text-white font-semibold">Register</h4>
                </div>
                <p class="text-white/60 text-sm">Create your account using your university student ID and email.</p>
            </div>

            <div class="glass rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg gradient-orange flex items-center justify-center text-white font-bold">2</div>
                    <h4 class="text-white font-semibold">Verify Email</h4>
                </div>
                <p class="text-white/60 text-sm">Confirm your email address to activate your account.</p>
            </div>

            <div class="glass rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg gradient-orange flex items-center justify-center text-white font-bold">3</div>
                    <h4 class="text-white font-semibold">Vote Securely</h4>
                </div>
                <p class="text-white/60 text-sm">Cast your ballot in any election you're eligible for and verify it was counted.</p>
            </div>
        </div>
    </div>
@endsection
