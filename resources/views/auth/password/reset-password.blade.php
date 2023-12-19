@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <label for="email">{{ __('Email') }}</label>
    <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required>
    @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password">{{ __('Password') }}</label>
    <input id="password" type="password" name="password" required autofocus>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label for="password-confirm">{{ __('Confirm Password') }}</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit">
        {{ __('Reset Password') }}
    </button>
    
</form>
@endsection