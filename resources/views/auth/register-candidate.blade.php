@extends('layouts.auth')

@section('title', 'Register as Candidate — Cornelect')

@section('auth-content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-navy-950 mb-2">Run for Office</h1>
        <p class="text-slate-500">Submit your candidate application for university elections</p>
    </div>

    {{-- Registration Type Tabs --}}
    <div class="flex border-b border-slate-200 mb-6">
        <a href="{{ route('register') }}" class="flex-1 auth-tab">Voter Registration</a>
        <div class="flex-1 auth-tab active">Candidate Registration</div>
    </div>

    <form method="POST" action="{{ route('register.candidate.post') }}" enctype="multipart/form-data" data-validate class="space-y-5">
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
            <label for="position_id" class="block text-sm font-semibold text-navy-950 mb-2">Position</label>
            <select id="position_id" name="position_id" class="form-input" required>
                <option value="">Select position</option>
                @foreach(\App\Models\Position::with('election')->get() as $position)
                    <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->title }} — {{ $position->election->title }}</option>
                @endforeach
            </select>
            @error('position_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
        </div>

        <div>
            <label for="manifesto_title" class="block text-sm font-semibold text-navy-950 mb-2">Manifesto Title</label>
            <input type="text" id="manifesto_title" name="manifesto_title" class="form-input" placeholder="My Vision for a Better Student Union" value="{{ old('manifesto_title') }}" required>
            @error('manifesto_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required</p> @enderror
        </div>

        <div>
            <label for="manifesto" class="block text-sm font-semibold text-navy-950 mb-2">Manifesto</label>
            <textarea id="manifesto" name="manifesto" rows="4" class="form-input resize-none" placeholder="Why do you want to run for this position? What will you do? (100-2000 characters)" required minlength="100" maxlength="2000">{{ old('manifesto') }}</textarea>
            <p class="text-slate-400 text-xs mt-1">Minimum 100 characters. This will be reviewed by administrators.</p>
            @error('manifesto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @else <p data-error class="hidden text-red-500 text-xs mt-1">Required (min 100 characters)</p> @enderror
        </div>

        <div>
            <label for="slogan" class="block text-sm font-semibold text-navy-950 mb-2">Slogan (optional)</label>
            <input type="text" id="slogan" name="slogan" class="form-input" placeholder="Together We Can" value="{{ old('slogan') }}">
            @error('slogan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="photo" class="block text-sm font-semibold text-navy-950 mb-2">Profile Photo (optional)</label>
            <input type="file" id="photo" name="photo" class="form-input" accept="image/*">
            <p class="text-slate-400 text-xs mt-1">Max 2MB. JPG, PNG, or GIF.</p>
            @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

        <div class="p-4 rounded-lg bg-orange-50 border border-orange-200">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <p class="text-orange-700 text-sm">
                    After admin approval, you'll receive an email with a token to complete your registration and set up your campaign profile.
                </p>
            </div>
        </div>

        <button type="submit" class="btn-primary w-full justify-center">
            Submit Application
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
    </form>

    <p class="text-center text-sm text-slate-600 mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-semibold">Sign In</a>
    </p>
@endsection

@section('auth-sidebar')
    <div class="animate-fade-in-right">
        <h2 class="text-4xl font-extrabold text-white mb-6 leading-tight">
            Lead the <span class="gradient-text">Change</span>
        </h2>
        <p class="text-white/70 text-lg mb-8 leading-relaxed">
            Become a candidate and make a difference in your university community. Share your vision with students.
        </p>

        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Create a campaign profile with manifesto</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Add social media links and campaign photos</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Track real-time vote counts</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/></svg>
                </div>
                <p class="text-white/80 text-sm">Engage with voters through campaign updates</p>
            </div>
        </div>
    </div>
@endsection
