<!-- In your profile show.blade.php view -->

@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       
    </script>
    <link rel="stylesheet" href="{{ asset('css/posts/individual_post.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <div class="container-post">
        <div class="post-info">
            <h1>{{ $post->title }}</h1>
            <div class="post-body">
                <p>{{ $post->description }}</p>
            </div>
            
            @if(Auth::user()->id == $post->user_id || Auth::user()->isAdmin()->exists())
                <!-- <a class="button" href="route" class="btn">Edit Post</a> -->
            @endif
        </div>
    </div>
    @if (Auth::check() && Auth::user()->id == $post->user_id)
    <div class="">
        <a href="{{ route('post.edit', $post->id) }}" class="button">Edit Post</a>
        <!-- The delete button can be a form to send a DELETE request -->
        <form action="{{ route('post.delete', $post->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="button">Delete Post</button>
        </form>
    </div>
@endif

    <div class="post-data">
        <div>
            Upvotes: <span id="upvotes-count-{{ $post->id }}">{{ $post->upvotes }}</span>
            Downvotes: <span id="downvotes-count-{{ $post->id }}">{{ $post->downvotes }}</span>
            <p>Posted on: {{ \Carbon\Carbon::parse($post->created_at)->format('F d, Y') }}</p>
            <p>Author: {{ $post->user->name }}</p> <!-- Display author's name -->
        </div>
    </div>
    
    <div class="interactive-post-buttons">
        <button onclick="upvotePost({{ $post->id }})" class="bi bi-arrow-up"></button>
        <button onclick="downvotePost({{ $post->id }})" class="bi bi-arrow-down"></button>
    </div>
    

    
    
 


    <div class="container-comment">
        <div class="comment-section">
            <h2>Comments</h2>
            <div class="comment-list">
                @foreach ($comments as $comment)
                    <div class="comment-item">
                        <div class="comment-header">
                            <h4></h4>
                            <a href="{{ route('profile.show', ['username' => $comment->user->username]) }}" class="user-link">{{ $comment->user->username }}</a> 
                            <span class="comment-date">{{ $comment->created_at }}</span>
                        </div>
                        <div class="comment-body">
                            <p>{{ $comment->text }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="add-comment">
            <h2>Add Comment</h2>
            <form method="POST" action="{{ route('comment.create', ['post_id' => $post->id, 'parent_comment_id' => '0']) }}">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="comment" required></textarea><br>
                <button type="submit">Add Comment</button>
            </form>
        </div>
@endsection



