@component('emails.layout')
    @slot('subject', "Voting is now open: {$election->title}")
    <h2>Voting is Now Open!</h2>
    <p>The election <strong>{{ $election->title }}</strong> is now active.</p>
    <p><strong>Positions:</strong> {{ $election->positions()->count() }}</p>
    <p><strong>Deadline:</strong> {{ $election->ends_at->format('M j, Y g:i A') }}</p>
    <a href="{{ route('voter.elections.show', $election) }}" class="btn">Cast Your Vote</a>
    <p>Your vote matters! Make your voice heard.</p>
@endcomponent
