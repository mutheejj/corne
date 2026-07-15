@component('emails.layout')
    @slot('subject', 'Your candidacy application status')
    <h2>Candidacy Update</h2>
    <p>We regret to inform you that your candidacy for <strong>{{ $candidate->position->title }}</strong> in <strong>{{ $candidate->election->title }}</strong> has not been approved.</p>
    @if ($reason ?? null)
        <p><strong>Reason:</strong> {{ $reason }}</p>
    @endif
    <p>If you believe this decision was made in error, you may appeal by contacting the election committee.</p>
    <p>Thank you for your interest in serving the student community.</p>
@endcomponent
