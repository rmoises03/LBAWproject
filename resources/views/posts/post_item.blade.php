<!-- In your profile show.blade.php view -->

@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       
    </script>
    <link rel="stylesheet" href="{{ asset('css/posts/individual_post.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <div class="container-post">
        <div class="post-flex-container">
            <div class="post-info">
                <h1>{{ $post->title }}</h1>
                <div class="post-body">
                    <p>{{ $post->description }}</p>
                </div>
                
            </div>
            <div class="post-data">
                <div>
                    <strong>
                    <span>{{ \Carbon\Carbon::parse($post->created_at)->format('F d, Y') }}</span>
                    <span>by  <a href="{{ route('profile.show', ['username' => $post->user->username]) }}">{{ $post->user->username }} </a></span>
                    </strong>
                </div>
            </div>
            <div class="post-data">
                <!-- Categories -->
                <div class="post-categories">
                    <span> <strong>Categories: </strong></span>
                    <span class="category-list">
                        @foreach ($post->categories as $category)
                            <a href="{{ route('category.show', $category->id) }}" class="category-link">{{ $category->name }}</a>
                        @endforeach
                    </span>
                </div>
            
                <!-- Tags -->
                <div class="post-tags">
                    <span><strong> Tags:</strong></span>
                    <span class="tag-list">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('tag.show', $tag->id) }}" class="tag-link">#{{ $tag->name }} </a>
                        @endforeach
                    </span>
                </div>
            </div>
            
            <div class="post-data">
                <div>
                    <span><strong>Upvotes: </strong><span id="upvotes-count-{{ $post->id }}">{{ $post->upvotes }}</span>
                    <span><strong>Downvotes: </strong> <span id="downvotes-count-{{ $post->id }}">{{ $post->downvotes }}</span>
                </div>
            </div>
            
            @auth
                @php
                    $upvoted = $postVote && $postVote->vote_type == 1;
                    $downvoted = $postVote && $postVote->vote_type == -1;
                @endphp

                <div class="interactive-post-buttons">
                    <span>
                        <button onclick="votePost({{ $post->id }}, 1)" class="bi bi-arrow-up {{ $upvoted ? 'upvoted-class' : '' }}"></button>
                    </span>
                    <span>
                        <button onclick="votePost({{ $post->id }}, -1)" class="bi bi-arrow-down {{ $downvoted ? 'downvoted-class' : '' }}"></button>
                    </span>
                </div>
            @endauth
        </div>
        @if (Auth::check() && Auth::user()->id == $post->user_id)
        <div class="post-data">
            <button type="button" onclick="openEditOverlay()">Edit Post</button>
            <!-- The delete button can be a form to send a DELETE request -->
            <button type="button" onclick="openDeleteOverlay('{{ route('post.delete', $post->id) }}')">Delete Post</button>
        </div>
        @endif
    </div>

    
    <div class="comment-section">
        <div class="add-comment">
            <h2>Add Comment</h2>
            <form method="POST" action="{{ route('comment.create', ['post_id' => $post->id, 'parent_comment_id' => '0']) }}">
                @csrf
                <textarea name="comment" required placeholder="Write a comment"></textarea><br>
                <button type="submit">Add Comment</button>
            </form>
        </div>
    </div>

    <div class="comment-section">
            <h2>Comments</h2>
            <div class="comment-list">
                @foreach ($comments as $comment)
                    @if ($comment->parent_comment_id == null)
                        @include('posts.comment', ['comment' => $comment, 'depth' => 0])
                    @endif
                @endforeach
            </div>
        
    </div>
    
    
    



    <div id="deletePostOverlay" class="overlay" style="display: none;">
        <div class="overlay-content">
            <span class="close-button" onclick="closeDeleteOverlay()">&times;</span>
            <h4>Are you sure you want to delete this post?</h4>
            <p>This action <strong>cannot</strong> be undone.</p>
            <form id="deletePostForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="button">Delete Post</button>
                <button type="button" onclick="closeDeleteOverlay()">Cancel</button>
            </form>
        </div>
    </div>

    <div id="editPostOverlay" class="overlay" style="display: none;">
        <div class="overlay-content">
            <span class="close-button" onclick="closeEditOverlay()">&times;</span>
            <form id="editPostForm" method="POST" action="{{ route('post.update', $post->id) }}">
                @csrf
                @method('PUT')
                <label for="title">Title:</label>
                <input type="text" id="edit_title" name="title" value="{{ $post->title }}" required>
                @if ($errors->has('title'))
                    <span class="error">
                        {{ $errors->first('title') }}
                    </span>
                @endif
    
                <label for="description">Description:</label>
                <textarea id="edit_description" name="description" required>{{ $post->description }}</textarea>
                @if ($errors->has('description'))
                    <span class="error">
                        {{ $errors->first('description') }}
                    </span>
                @endif

                <label for="category">Category:</label>
                <select id="edit_category" name="category" required>
                    <option value="{{ old('category') }}" disabled selected>Select a Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $post->categories->contains($category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('category'))
                    <span class="error">
                        {{ $errors->first('category') }}
                    </span>
                @endif

                <label for="tags">Tags:</label>
                <select id="edit_tags" name="tags[]" multiple>
                    <option value="" {{ empty($post->tags) ? 'selected' : '' }}>No selection</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('tags'))
                    <span class="error">
                        {{ $errors->first('tags') }}
                    </span>
                @endif

                <button type="submit" class="button">Update Post</button>
                <button type="button" onclick="closeEditOverlay()">Cancel</button>
            </form>
        </div>
    </div>

    

@endsection





