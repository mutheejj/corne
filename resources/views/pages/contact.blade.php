@extends('layouts.app')

@section('title', 'Contact Us — Cornelect')
@section('meta-description', "Get in touch with the Cornelect team. We're here to help with any questions about university voting.")

@section('content')

{{-- Hero --}}
<section class="relative pt-32 pb-20 gradient-mesh overflow-hidden">
    <div class="glow-orb glow-orb-orange" style="width: 500px; height: 500px; top: -150px; right: -100px;"></div>
    <div class="absolute inset-0 grid-pattern opacity-20"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="badge badge-orange mb-6 animate-fade-in-down">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Get in Touch
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 animate-fade-in-up">
            Contact <span class="gradient-text">Cornelect</span>
        </h1>
        <p class="text-white/70 text-lg lg:text-xl max-w-2xl mx-auto animate-fade-in-up delay-200">
            Have questions about Cornelect? We're here to help. Reach out and we'll get back to you within 24 hours.
        </p>
    </div>
</section>

{{-- Contact Section --}}
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16">

            {{-- Contact Info --}}
            <div class="reveal-left">
                <h2 class="text-3xl font-extrabold text-navy-950 mb-6">Let's Talk</h2>
                <p class="text-slate-500 text-lg mb-8 leading-relaxed">
                    Whether you're a student with questions about voting, an administrator looking to set up elections, or a university official exploring our platform — we'd love to hear from you.
                </p>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="icon-container icon-container-orange flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-navy-950 mb-1">Email</h4>
                            <p class="text-slate-500 text-sm">support@unielect.ac.ke</p>
                            <p class="text-slate-500 text-sm">info@unielect.ac.ke</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="icon-container icon-container-orange flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-navy-950 mb-1">Phone</h4>
                            <p class="text-slate-500 text-sm">+254 700 000 000</p>
                            <p class="text-slate-500 text-sm">Mon-Fri, 8am - 6pm EAT</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="icon-container icon-container-orange flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-navy-950 mb-1">Office</h4>
                            <p class="text-slate-500 text-sm">Cornelect HQ</p>
                            <p class="text-slate-500 text-sm">Juja, Kiambu, Kenya</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200">
                    <h4 class="font-bold text-navy-950 mb-4">Follow Us</h4>
                    <div class="flex items-center gap-4">
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 hover:text-orange-500 hover:bg-orange-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.1 1.5 4.3c2.5 2.7 5.8 4.3 9.5 4.5C9.5 1.5 14.5.5 18 3.4c1.6-.2 3.1-.8 4.3-1.6.1 1.2-.5 2.4-1.3 3.2z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 hover:text-orange-500 hover:bg-orange-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 hover:text-orange-500 hover:bg-orange-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 hover:text-orange-500 hover:bg-orange-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="reveal-right">
                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-200">
                    <h3 class="text-xl font-bold text-navy-950 mb-6">Send us a message</h3>

                    <form method="POST" action="{{ route('contact.post') }}" data-validate class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-navy-950 mb-2">Full Name</label>
                                <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required>
                                <p data-error class="hidden text-red-500 text-xs mt-1">Required</p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-navy-950 mb-2">Email</label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="you@university.ac.ke" required>
                                <p data-error class="hidden text-red-500 text-xs mt-1">Required</p>
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-semibold text-navy-950 mb-2">Subject</label>
                            <select id="subject" name="subject" class="form-input" required>
                                <option value="">Select a topic</option>
                                <option>General Inquiry</option>
                                <option>Technical Support</option>
                                <option>Election Setup</option>
                                <option>Partnership</option>
                                <option>Security Question</option>
                                <option>Other</option>
                            </select>
                            <p data-error class="hidden text-red-500 text-xs mt-1">Required</p>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold text-navy-950 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" class="form-input resize-none" placeholder="Tell us how we can help..." required></textarea>
                            <p data-error class="hidden text-red-500 text-xs mt-1">Required</p>
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center">
                            Send Message
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
