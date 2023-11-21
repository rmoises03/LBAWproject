<!-- resources/views/profiles/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div>
        <!-- profiles/edit.blade.php -->
        <h1>Edit Profile</h1>

        <form method="POST" action="{{ route('profile.update', ['username' => $user->username]) }}">
            @csrf
            @method('PUT')

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{{ $user->username }}" required>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ $user->date_of_birth }}" required>

            <label for="reputation">Reputation:</label>
            <input type="number" id="reputation" name="reputation" value="{{ $user->reputation }}" required class="uneditable">

            <button type="submit">Save Changes</button>
        </form>
    </div>
@endsection
