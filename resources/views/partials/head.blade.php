<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Cornelect — University Voting System')</title>
<meta name="description" content="@yield('meta-description', 'Cornelect is a secure, transparent, and easy-to-use online voting platform designed for universities. Cast your vote with confidence.')">
<meta name="keywords" content="@yield('meta-keywords', 'university voting, online voting, student elections, secure voting, Cornelect')">
<meta property="og:title" content="@yield('og-title', 'Cornelect — University Voting System')">
<meta property="og:description" content="@yield('og-description', 'Secure, transparent, and easy-to-use online voting for universities.')">
<meta property="og:type" content="website">
<meta name="theme-color" content="#0a1628">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
