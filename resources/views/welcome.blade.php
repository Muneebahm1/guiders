@extends('layouts.app')

@section('content')
<div class="auth-wrap">
    <div class="auth-card text-center">
        <img src="{{ asset('img/logo/logoguidr.png') }}" alt="The Guiders" class="auth-logo">
        <h1>{{ config('app.name', 'The Guiders') }}</h1>
        <p class="text-muted mb-4">Study abroad and research-publication guidance, from lead to visa approval.</p>

        @guest
            <a href="{{ route('login') }}" class="btn btn-primary w-100">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-2">Register</a>
        @else
            <a href="{{ route('home') }}" class="btn btn-primary w-100">Go to Dashboard</a>
        @endguest
    </div>
</div>
@endsection
