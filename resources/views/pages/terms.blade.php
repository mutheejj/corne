@extends('layouts.app')

@section('title', 'Terms of Service — Cornelect')
@section('meta-description', 'Cornelect Terms of Service — The terms and conditions for using our university voting platform.')

@section('content')

<section class="relative pt-32 pb-20 gradient-mesh overflow-hidden">
    <div class="glow-orb glow-orb-orange" style="width: 400px; height: 400px; top: -100px; left: -50px;"></div>
    <div class="absolute inset-0 grid-pattern opacity-20"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 animate-fade-in-up">Terms of <span class="gradient-text">Service</span></h1>
        <p class="text-white/70 text-lg animate-fade-in-up delay-200">Last updated: {{ date('F j, Y') }}</p>
    </div>
</section>

<section class="section-padding bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-10">

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">1. Acceptance of Terms</h2>
                <p class="text-slate-600 leading-relaxed">By accessing or using Cornelect, you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you must not use the platform.</p>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">2. Eligibility</h2>
                <p class="text-slate-600 leading-relaxed">Cornelect is designed for university students, faculty, and administrators. You must be a registered student or authorized staff member of a participating university to use this platform. You must provide accurate and truthful information during registration.</p>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">3. User Responsibilities</h2>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Provide accurate registration information using your valid university student ID.</span></li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Maintain the confidentiality of your account credentials.</span></li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Vote only once per election and only in elections you are eligible for.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Do not attempt to manipulate, disrupt, or compromise the voting system.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Do not share your verification codes or receipt hashes with others.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Report any security vulnerabilities or suspicious activity to administrators.</li>
                </ul>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">4. Voting Integrity</h2>
                <p class="text-slate-600 leading-relaxed mb-4">All votes are final once submitted. You cannot change your vote after casting it. The system uses cryptographic techniques to ensure:</p>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>One vote per person per election.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Ballot anonymity — your choices are not linked to your identity.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Vote verifiability — you can confirm your vote was counted.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Results integrity — results cannot be tampered with after calculation.</li>
                </ul>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">5. Candidate Conduct</h2>
                <p class="text-slate-600 leading-relaxed">Candidates must provide truthful information in their applications and campaign materials. Campaign content must not include hate speech, defamation, or false claims about other candidates. Administrators reserve the right to reject, disqualify, or remove candidates who violate these terms.</p>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">6. Prohibited Activities</h2>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Attempting to vote multiple times or on behalf of others.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Using bots, scripts, or automated tools to interact with the platform.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Attempting to access or modify another user's account or data.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Distributing malware or attempting to exploit vulnerabilities.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Harassing or intimidating voters or candidates.</li>
                </ul>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">7. Dispute Resolution</h2>
                <p class="text-slate-600 leading-relaxed">Election results may be contested through the official dispute resolution process of your university. Cornelect provides audit logs and verification tools to support dispute resolution. The final authority on election outcomes rests with the university's election committee or governing body.</p>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">8. Limitation of Liability</h2>
                <p class="text-slate-600 leading-relaxed">Cornelect is provided "as is" without warranties of any kind. We are not liable for indirect, incidental, or consequential damages arising from the use of the platform. Our maximum liability shall not exceed the fees paid by your institution for the service.</p>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">9. Changes to Terms</h2>
                <p class="text-slate-600 leading-relaxed">We may update these Terms of Service from time to time. Users will be notified of significant changes via email or in-app notification. Continued use of the platform after changes constitutes acceptance of the updated terms.</p>
            </div>

            <div class="reveal">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">10. Contact</h2>
                <p class="text-slate-600 leading-relaxed">For questions about these Terms, contact us at <a href="mailto:legal@unielect.ac.ke" class="text-orange-600 font-semibold">legal@unielect.ac.ke</a>.</p>
            </div>
        </div>
    </div>
</section>

@endsection
