@extends('layouts.app')

@section('title', 'Cornelect — Secure Online Voting for Universities')

@section('content')
    @include('partials.home._hero')
    @include('partials.home._stats')
    @include('partials.home._features')
    @include('partials.home._how-it-works')
        @include('partials.home._election-types')
    @include('partials.home._testimonials')
    @include('partials.home._faq')
    @include('partials.home._cta')
@endsection
