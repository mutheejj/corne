@component('emails.layout')
    @slot('subject', 'Your candidacy has been approved')
    <h2>Congratulations!</h2>
    <p>Your candidacy for <strong>{{ $candidate->position->title }}</strong> in <strong>{{ $candidate->election->title }}</strong> has been approved.</p>
    <p>You can now manage your campaign profile and interact with voters.</p>
    <a href="{{ route('candidate.dashboard') }}" class="btn">View Dashboard</a>
    <p>Good luck with your campaign!</p>
@endcomponent
