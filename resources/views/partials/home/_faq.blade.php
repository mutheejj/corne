{{-- FAQ Section --}}
<section class="section-padding bg-white relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16 reveal">
            <div class="badge badge-orange mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                FAQ
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-navy-950 mb-4">
                Frequently Asked <span class="gradient-text">Questions</span>
            </h2>
            <p class="text-slate-500 text-lg">
                Everything you need to know about Cornelect and how it works.
            </p>
        </div>

        {{-- FAQ Items --}}
        <div class="space-y-4">

            <div data-accordion class="border border-slate-200 rounded-xl overflow-hidden reveal">
                <button data-accordion-trigger class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 transition-colors text-left">
                    <span class="font-semibold text-navy-950">How does Cornelect ensure vote anonymity?</span>
                    <svg data-accordion-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500 transition-transform"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div data-accordion-content class="accordion-content">
                    <p class="p-5 text-slate-500 text-sm leading-relaxed">
                        Cornelect separates voter identity from ballot choices using cryptographic techniques. When you cast your vote, it's encrypted and stored separately from your identity. Each vote generates a unique verification code that lets you confirm your vote was counted — without revealing who you voted for.
                    </p>
                </div>
            </div>

            <div data-accordion class="border border-slate-200 rounded-xl overflow-hidden reveal delay-100">
                <button data-accordion-trigger class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 transition-colors text-left">
                    <span class="font-semibold text-navy-950">Can I verify that my vote was counted?</span>
                    <svg data-accordion-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500 transition-transform"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div data-accordion-content class="accordion-content">
                    <p class="p-5 text-slate-500 text-sm leading-relaxed">
                        Yes! After casting your vote, you receive a unique verification code and a cryptographic receipt hash. You can use the public vote verification tool to confirm your vote was recorded in the system without revealing your ballot choices.
                    </p>
                </div>
            </div>

            <div data-accordion class="border border-slate-200 rounded-xl overflow-hidden reveal delay-200">
                <button data-accordion-trigger class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 transition-colors text-left">
                    <span class="font-semibold text-navy-950">What devices can I use to vote?</span>
                    <svg data-accordion-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500 transition-transform"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div data-accordion-content class="accordion-content">
                    <p class="p-5 text-slate-500 text-sm leading-relaxed">
                        Cornelect works on any device with a web browser — smartphones, tablets, laptops, and desktop computers. The platform is fully responsive and optimized for all screen sizes. No app download is required.
                    </p>
                </div>
            </div>

            <div data-accordion class="border border-slate-200 rounded-xl overflow-hidden reveal delay-300">
                <button data-accordion-trigger class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 transition-colors text-left">
                    <span class="font-semibold text-navy-950">How are election administrators chosen?</span>
                    <svg data-accordion-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500 transition-transform"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div data-accordion-content class="accordion-content">
                    <p class="p-5 text-slate-500 text-sm leading-relaxed">
                        University administrators are invited by the Super Admin (typically the university's IT or Student Affairs department). Admins and moderators have role-based permissions that control what they can access and manage within the system.
                    </p>
                </div>
            </div>

            <div data-accordion class="border border-slate-200 rounded-xl overflow-hidden reveal delay-400">
                <button data-accordion-trigger class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 transition-colors text-left">
                    <span class="font-semibold text-navy-950">What happens if an election is disrupted?</span>
                    <svg data-accordion-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500 transition-transform"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div data-accordion-content class="accordion-content">
                    <p class="p-5 text-slate-500 text-sm leading-relaxed">
                        Cornelect includes emergency controls for administrators, including the ability to pause, resume, or cancel elections. All actions are logged in the audit trail. The system also supports automated backup and recovery to ensure no data is lost.
                    </p>
                </div>
            </div>

            <div data-accordion class="border border-slate-200 rounded-xl overflow-hidden reveal delay-500">
                <button data-accordion-trigger class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 transition-colors text-left">
                    <span class="font-semibold text-navy-950">Can I run as a candidate in an election?</span>
                    <svg data-accordion-icon xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500 transition-transform"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div data-accordion-content class="accordion-content">
                    <p class="p-5 text-slate-500 text-sm leading-relaxed">
                        Yes! Any registered student can apply as a candidate through the candidate pre-registration flow. You'll submit your details, faculty, department, intended position, and a manifesto. After admin approval, you'll receive a token to complete your registration and set up your campaign profile.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
