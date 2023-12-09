<!-- In your profile show.blade.php view -->

@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <div class="container-profile">
        <div id="contact" class="container">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <strong>{{$message}}</strong>
                </div>

                <img src="{{ asset('images/'.Session::get('image')) }}" />
            @endif

            <form method="POST" action="{{ route('image.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" class="form-control" name="image" />

                <button type="submit" class="btn btn-sm">Upload</button>
            </form>

        </div>
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



