<!-- In your profile show.blade.php view -->

@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <div class="container-profile">
        <img src="{{ $user->profile_picture }}" alt="Profile Picture" class="profile-picture">
        <div class="profile-info">
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->description }}</p>

            <div>
                <strong>Username: {{ $user->username }}</strong>
                <strong>Date of Birth: {{ $user->date_of_birth }}</strong>
                <strong>Reputation: {{ $user->reputation }}</strong>
            </div>
            
            @if(Auth::user()->id == $user->id || Auth::user()->isAdmin()->exists())
                <a class="button" href="{{ route('profile.edit', ['username' => $user->username]) }}" class="btn">Edit Profile</a>
            @endif


            <!-- resources/views/profiles/show.blade.php -->

            <div class="user-posts">
                <h3>Posts</h3>
                @forelse ($user->posts as $post)
                    <div class="post">
                        <h4>{{ $post->title }}</h4>
                        <p>{{ $post->content }}</p>
                        <!-- Add more post details if needed -->
                    </div>
                @empty
                    <p>No posts to display.</p>
                @endforelse
            </div>


            <a class="button" href="{{ route('login') }}" class="btn">Go Back</a>
        </div>
    </div>
@endsection



