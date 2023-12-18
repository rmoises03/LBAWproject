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

            <div>
                <form method="POST" action="{{ route('profile.destroy', ['username' => $user->username]) }}">
                    @csrf
                    @method('DELETE')

                    <button class="button" id="delete" type="submit" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
                </form>
            </div>
        @endif


            <div class="user-posts">
                <h3>Posts</h3>
                <div class="scrollable-content">
                    @forelse ($user->posts as $post)
                        <div class="post">
                            <h4>{{ $post->title }}</h4>
                            <p>{{ $post->description }}</p>
                        </div>
                    @empty
                        <p>No posts to display.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="user-comments">
                <h3>Comments</h3>
                <div class="scrollable-content">
                    @forelse ($user->comments as $comment)
                        <div class="comment">
                            @if ($comment->post)
                                <a href="{{ route('post.open', ['id' => $comment->post->id]) }}">
                                    <p>{{ $comment->post->title }}</p>
                                </a>
                            @else
                                <p>Post not found</p>
                            @endif
                            <p><strong>{{ $comment->text }}</strong></p>
                        </div>
                    @empty
                        <p>No comments to display.</p>
                    @endforelse
                </div>
            </div>
            
            


            <a class="button" href="{{ url()->previous() }}">Go Back</a>
        </div>
    </div>
@endsection



