@component('emails.layout')
    @slot('subject', 'Reset your Cornelect password')
    <h2>Password Reset Request</h2>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
    <p>This password reset link will expire in 60 minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>
@endcomponent
