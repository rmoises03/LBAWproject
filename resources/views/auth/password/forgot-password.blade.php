@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <label for="email">{{ __('Email') }}</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
    @endif

    <button type="submit">{{ __('Send Password Reset Link') }}</button>
    
    @if(session('status'))
    <span class="alert alert-success">
        {{ session('status') }}
    </span>
    @endif

</form>
@endsection