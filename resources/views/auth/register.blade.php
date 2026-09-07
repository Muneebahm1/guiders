@extends('layouts.app')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <img src="{{ asset('img/logo/logoguidr.png') }}" alt="The Guiders" class="auth-logo">
        <h1>{{ __('Create your account') }}</h1>
        <div class="auth-sub">{{ __('Join The Guiders') }}</div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">{{ __('I am a') }}</label>
                <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                    <option value="student" @selected(old('role') === 'student')>{{ __('Student') }}</option>
                    <option value="counselor" @selected(old('role') === 'counselor')>{{ __('Counselor') }}</option>
                    <option value="partner" @selected(old('role') === 'partner')>{{ __('Partner Company') }}</option>
                </select>
                @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </form>

        <div class="auth-footer">
            {{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Login') }}</a>
        </div>
    </div>
</div>
@endsection
