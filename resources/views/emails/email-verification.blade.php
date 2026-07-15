@component('emails.layout')
    @slot('subject', 'Verify your email — Cornelect')
    <h2>Verify Your Email Address</h2>
    <p>Please verify your email address to complete your registration.</p>
    <a href="{{ $verificationUrl }}" class="btn">Verify Email</a>
    <p>If you did not create an account, no further action is required.</p>
@endcomponent
