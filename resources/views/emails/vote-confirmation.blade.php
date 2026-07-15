@component('emails.layout')
    @slot('subject', "Vote confirmation — {$election->title}")
    <h2>Vote Confirmed</h2>
    <p>Your vote for <strong>{{ $positionTitle }}</strong> has been recorded.</p>
    <p><strong>Verification Code:</strong> {{ $verificationCode }}</p>
    <p><strong>Receipt Hash:</strong> {{ $receiptHash }}</p>
    <p>Please save this information for your records.</p>
    <a href="{{ route('voter.vote-history') }}" class="btn">Verify Your Vote</a>
    <p>Thank you for voting!</p>
@endcomponent
