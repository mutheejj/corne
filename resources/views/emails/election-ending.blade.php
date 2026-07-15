@component('emails.layout')
    @slot('subject', "Reminder: {$election->title} ends in {$hours} hours")
    <h2>Election Ends Soon!</h2>
    <p>The election <strong>{{ $election->title }}</strong> ends in <strong>{{ $hours }} hour(s)</strong>.</p>
    <p>Don't miss your chance to vote!</p>
    <a href="{{ route('voter.elections.show', $election) }}" class="btn">Cast Your Vote Now</a>
@endcomponent
