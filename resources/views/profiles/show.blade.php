<!-- resources/views/profiles/show.blade.php -->

@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <div>
        <img src="{{ $user->profile_picture }}" alt="Profile Picture">
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->description }}</p>

        <div>
            <strong>Username: {{ $user->username }}</strong>
            <strong>Date of Birth: {{ $user->date_of_birth }}</strong>
            <strong>Reputation: {{ $user->reputation }}</strong>
        </div>
    </div>
@endsection
