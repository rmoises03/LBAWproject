<!-- resources/views/profiles/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <!-- profiles/edit.blade.php -->
    <h1>Edit Profile</h1>

    <form method="POST" action="{{ route('profile.update', ['username' => $user->username]) }}">
        @csrf
        @method('PUT')

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="{{ $user->username }}" required>
        @if ($errors->has('username'))
            <span class="error">{{ $errors->first('username') }}</span>
        @endif

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ $user->date_of_birth }}" required>
        @if ($errors->has('date_of_birth'))
            <span class="error">{{ $errors->first('date_of_birth') }}</span>
        @endif

        <button type="submit">Save Changes</button>
    </form>
@endsection
