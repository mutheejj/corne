@extends('layouts.app')

@section('title', 'Features — Cornelect')
@section('meta-description', 'Explore all the powerful features of Cornelect — secure voting, real-time results, candidate management, audit trails, and more.')

@section('content')

{{-- Hero --}}
<section class="relative pt-32 pb-20 gradient-mesh overflow-hidden">
    <div class="glow-orb glow-orb-orange" style="width: 500px; height: 500px; top: -150px; left: -100px;"></div>
    <div class="absolute inset-0 grid-pattern opacity-20"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="badge badge-orange mb-6 animate-fade-in-down">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v6.5l3-3 3 3"/><path d="M3 12h6.5l-3 3 3 3"/><path d="M12 22v-6.5l3 3 3-3"/><path d="M21 12h-6.5l3-3-3-3"/></svg>
            Platform Features
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 animate-fade-in-up">
            Everything You Need for
            <span class="gradient-text block mt-2">Secure Elections</span>
        </h1>
        <p class="text-white/70 text-lg lg:text-xl max-w-2xl mx-auto animate-fade-in-up delay-200">
            A comprehensive suite of tools designed to make university voting simple, secure, and transparent.
        </p>
    </div>
</section>

{{-- Feature Categories --}}
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Authentication & Security --}}
        <div class="mb-20">
            <div class="flex items-center gap-3 mb-8 reveal">
                <div class="icon-container icon-container-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-navy-950">Authentication & Security</h2>
                    <p class="text-slate-500 text-sm">Multi-layered security to protect every vote</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> JWT Authentication</h4>
                    <p class="text-slate-500 text-sm">Access and refresh token rotation with server-side revocation for secure session management.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Two-Factor Authentication</h4>
                    <p class="text-slate-500 text-sm">Optional TOTP-based 2FA for vote casting and admin access, adding an extra layer of security.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Rate Limiting</h4>
                    <p class="text-slate-500 text-sm">Multi-tier rate limiting for auth, voting, uploads, and admin endpoints to prevent abuse.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Vote Encryption</h4>
                    <p class="text-slate-500 text-sm">Client-side and server-side encryption using crypto-js with managed cryptographic keys.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Verification Codes</h4>
                    <p class="text-slate-500 text-sm">Unique verification codes and receipt hashes for each vote, enabling post-voting verification.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Anonymous Voting</h4>
                    <p class="text-slate-500 text-sm">Optional anonymous voting mode that separates voter identity from ballot choices.</p>
                </div>
            </div>
        </div>

        {{-- Election Management --}}
        <div class="mb-20">
            <div class="flex items-center gap-3 mb-8 reveal">
                <div class="icon-container icon-container-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-navy-950">Election Management</h2>
                    <p class="text-slate-500 text-sm">Complete lifecycle management for every type of election</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Automated Lifecycle</h4>
                    <p class="text-slate-500 text-sm">Elections auto-schedule, auto-start, and auto-complete with real-time status broadcasting.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Multiple Election Types</h4>
                    <p class="text-slate-500 text-sm">Presidential, student union, departmental, faculty, club, society, referendum, and poll elections.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Eligibility Filters</h4>
                    <p class="text-slate-500 text-sm">Per-election eligibility based on faculty, department, course, and year of study.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Multi-Position Ballots</h4>
                    <p class="text-slate-500 text-sm">Support for multiple positions per election with configurable max votes and candidates.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Live Results Toggle</h4>
                    <p class="text-slate-500 text-sm">Admins can enable or disable real-time results visibility during active elections.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Emergency Controls</h4>
                    <p class="text-slate-500 text-sm">Pause, resume, cancel, or trigger emergency shutdown with full audit logging.</p>
                </div>
            </div>
        </div>

        {{-- Candidate & Campaign --}}
        <div class="mb-20">
            <div class="flex items-center gap-3 mb-8 reveal">
                <div class="icon-container icon-container-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-navy-950">Candidate & Campaign Management</h2>
                    <p class="text-slate-500 text-sm">From application to campaign trail</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Pre-Registration Flow</h4>
                    <p class="text-slate-500 text-sm">Public candidate application with admin approval and token-based registration completion.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Campaign Profiles</h4>
                    <p class="text-slate-500 text-sm">Candidates create profiles with bio, manifesto, campaign slogan, photos, and social media links.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Running Mates</h4>
                    <p class="text-slate-500 text-sm">Support for paired candidacies with running mate management for presidential tickets.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Status Management</h4>
                    <p class="text-slate-500 text-sm">Pending, approved, rejected, disqualified, and withdrawn statuses with bulk operations.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Campaign Analytics</h4>
                    <p class="text-slate-500 text-sm">Track view counts, engagement metrics, and supporter counts for campaign performance.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Data Export</h4>
                    <p class="text-slate-500 text-sm">Export candidate data in JSON, CSV, or XLSX format for external analysis.</p>
                </div>
            </div>
        </div>

        {{-- Results & Analytics --}}
        <div class="mb-20">
            <div class="flex items-center gap-3 mb-8 reveal">
                <div class="icon-container icon-container-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-navy-950">Results & Analytics</h2>
                    <p class="text-slate-500 text-sm">Real-time results with comprehensive analytics</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Real-Time Results</h4>
                    <p class="text-slate-500 text-sm">Live result updates during active elections with per-position breakdowns and rankings.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Automated Calculation</h4>
                    <p class="text-slate-500 text-sm">Final results auto-calculated when elections end, with winner determination and tie detection.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Results Integrity</h4>
                    <p class="text-slate-500 text-sm">Cryptographic verification ensures results haven't been tampered with post-election.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Comparative Reports</h4>
                    <p class="text-slate-500 text-sm">Compare results across 2-10 elections side-by-side with historical comparison tools.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Results Certificate</h4>
                    <p class="text-slate-500 text-sm">Generate official results certificates for public record and institutional archiving.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Multi-Format Export</h4>
                    <p class="text-slate-500 text-sm">Export results in PDF, Excel, CSV, JSON, or HTML formats with scheduled report generation.</p>
                </div>
            </div>
        </div>

        {{-- Notifications & Real-Time --}}
        <div class="mb-20">
            <div class="flex items-center gap-3 mb-8 reveal">
                <div class="icon-container icon-container-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-navy-950">Notifications & Real-Time</h2>
                    <p class="text-slate-500 text-sm">Keep everyone informed, in real-time</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Multi-Channel Notifications</h4>
                    <p class="text-slate-500 text-sm">In-app, email, SMS, and push notifications with configurable user preferences.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Automated Reminders</h4>
                    <p class="text-slate-500 text-sm">Automatic election start and end reminders sent to eligible voters via bulk email.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> WebSocket Updates</h4>
                    <p class="text-slate-500 text-sm">Real-time vote counts, election status changes, and live results via WebSocket channels.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Admin Broadcasts</h4>
                    <p class="text-slate-500 text-sm">System-wide announcements, maintenance notifications, and security alerts.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Offline Delivery</h4>
                    <p class="text-slate-500 text-sm">Notifications stored in Redis and delivered on reconnection — never miss an update.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Notification Types</h4>
                    <p class="text-slate-500 text-sm">Election, system, reminder, security, campaign, and candidate notifications with priority levels.</p>
                </div>
            </div>
        </div>

        {{-- Audit & Compliance --}}
        <div>
            <div class="flex items-center gap-3 mb-8 reveal">
                <div class="icon-container icon-container-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-navy-950">Audit & Compliance</h2>
                    <p class="text-slate-500 text-sm">Complete transparency with full audit trails</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Comprehensive Audit Logs</h4>
                    <p class="text-slate-500 text-sm">Every system action logged with user, category, severity, entity, IP, and metadata.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Security Events</h4>
                    <p class="text-slate-500 text-sm">Track login attempts, suspicious activity, account lockouts, and unauthorized access.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Compliance Reports</h4>
                    <p class="text-slate-500 text-sm">Generate compliance reports with date ranges and audit integrity verification.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Audit Export</h4>
                    <p class="text-slate-500 text-sm">Export audit logs in JSON, Excel, or CSV format with advanced filtering.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-100">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Auto-Archiving</h4>
                    <p class="text-slate-500 text-sm">Automatic daily archiving of audit logs older than one year with cleanup tools.</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 card-hover reveal delay-200">
                    <h4 class="font-bold text-navy-950 mb-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Backup & Recovery</h4>
                    <p class="text-slate-500 text-sm">Full, incremental, manual, and automatic backups with restore and download capabilities.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.home._cta')

@endsection
