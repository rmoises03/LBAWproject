<!-- In your profile show.blade.php view -->

@extends('layouts.app')

@section('content')
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
    <div class="post-data">
        <div>
            Upvotes : <p> {{$post->upvotes}} </p> 
            Downvotes : <p> {{$post->downvotes}} </p> 
        </div>
    </div>
    <div class="float-right interactive-post-buttons">
       <a href={{route('post.upvote', ['post_id' => $post->id])}} ><button class="bi bi-arrow-up"></button> </a>
        <button class="bi bi-arrow-down"></button>
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



