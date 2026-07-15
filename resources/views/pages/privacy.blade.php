@extends('layouts.app')

@section('title', 'Privacy Policy — Cornelect')
@section('meta-description', 'Cornelect Privacy Policy — How we collect, use, and protect your data.')

@section('content')

<section class="relative pt-32 pb-20 gradient-mesh overflow-hidden">
    <div class="glow-orb glow-orb-orange" style="width: 400px; height: 400px; top: -100px; right: -50px;"></div>
    <div class="absolute inset-0 grid-pattern opacity-20"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 animate-fade-in-up">Privacy <span class="gradient-text">Policy</span></h1>
        <p class="text-white/70 text-lg animate-fade-in-up delay-200">Last updated: {{ date('F j, Y') }}</p>
    </div>
</section>

<section class="section-padding bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-slate max-w-none">

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">1. Introduction</h2>
                <p class="text-slate-600 leading-relaxed">Cornelect is committed to protecting your privacy. This policy explains how we collect, use, and safeguard your personal data when you use our online voting platform for universities.</p>
            </div>

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">2. Data We Collect</h2>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span><span><strong>Account Data:</strong> Student ID, email, name, phone number, faculty, department, course, year of study.</span></li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span><span><strong>Voting Data:</strong> Verification codes, receipt hashes, voting session metadata. Ballot choices are anonymized and separated from voter identity.</span></li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span><span><strong>Security Data:</strong> IP addresses, user agents, audit logs, security event records.</span></li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span><span><strong>Preference Data:</strong> Notification settings, theme, language, accessibility preferences.</span></li>
                </ul>
            </div>

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">3. How We Use Your Data</h2>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>To verify voter eligibility and prevent duplicate voting.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>To send election notifications, reminders, and results.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span><span>To maintain audit trails for election integrity and compliance.</span></li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>To provide vote verification and receipt functionality.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>To detect and prevent fraud, abuse, and unauthorized access.</li>
                </ul>
            </div>

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">4. Data Security</h2>
                <p class="text-slate-600 leading-relaxed mb-4">We employ industry-leading security measures:</p>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>256-bit encryption for all vote data (client-side and server-side).</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>JWT-based authentication with refresh token rotation.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Rate limiting and input sanitization on all endpoints.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Comprehensive audit logging of all system actions.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Regular security audits and vulnerability assessments.</li>
                </ul>
            </div>

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">5. Vote Anonymity</h2>
                <p class="text-slate-600 leading-relaxed">Your ballot choices are cryptographically separated from your identity. When you cast a vote, the system records that you voted (to prevent double voting) but cannot link your identity to your ballot choices. Verification codes allow you to confirm your vote was counted without revealing how you voted.</p>
            </div>

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">6. Data Retention</h2>
                <p class="text-slate-600 leading-relaxed">Election data and audit logs are retained for a minimum of one year after election completion, as required for compliance and dispute resolution. Audit logs older than one year are automatically archived. You may request deletion of your personal data subject to legal and compliance requirements.</p>
            </div>

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">7. Your Rights</h2>
                <ul class="space-y-3 text-slate-600">
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Access your personal data and voting history.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Update your profile and preferences.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Request data export in machine-readable format.</li>
                    <li class="flex items-start gap-3"><span class="w-2 h-2 rounded-full bg-orange-500 mt-2 flex-shrink-0"></span>Request account deletion (subject to compliance requirements).</li>
                </ul>
            </div>

            <div class="reveal mb-10">
                <h2 class="text-2xl font-extrabold text-navy-950 mb-4">8. Contact</h2>
                <p class="text-slate-600 leading-relaxed">For privacy questions or data requests, contact us at <a href="mailto:privacy@unielect.ac.ke" class="text-orange-600 font-semibold">privacy@unielect.ac.ke</a>.</p>
            </div>
        </div>
    </div>
</section>

@endsection
