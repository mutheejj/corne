@component('emails.layout')
    @slot('subject', "Results published: {$election->title}")
    <h2>Results Published</h2>
    <p>The results for <strong>{{ $election->title }}</strong> have been published.</p>
    <a href="{{ route('voter.elections.results', $election) }}" class="btn">View Results</a>
    <p>Thank you for participating!</p>
@endcomponent
