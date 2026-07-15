<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Cornelect Notification' }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #0a1628; padding: 24px; text-align: center; }
        .header h1 { color: #f97316; margin: 0; font-size: 28px; }
        .header p { color: #94a3b8; margin: 4px 0 0; font-size: 14px; }
        .body { padding: 32px 24px; color: #1e293b; line-height: 1.6; }
        .body h2 { color: #0f2942; margin-top: 0; }
        .btn { display: inline-block; background-color: #f97316; color: #ffffff; text-decoration: none; padding: 12px 32px; border-radius: 6px; font-weight: 600; margin: 16px 0; }
        .footer { background-color: #0a1628; padding: 16px 24px; text-align: center; color: #64748b; font-size: 12px; }
        .footer a { color: #f97316; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cornelect</h1>
            <p>Secure University Elections</p>
        </div>
        <div class="body">
            {{ $slot }}
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Cornelect. All rights reserved.</p>
            <p><a href="{{ route('home') }}">Visit Cornelect</a> | <a href="#">Notification Settings</a></p>
        </div>
    </div>
</body>
</html>
