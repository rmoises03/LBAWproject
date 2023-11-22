@extends('layouts.app')

@section('content')
    <h2>Search Results</h2>

    <div class="search-results">
        <div class="search-category">
            <h3>Posts</h3>
            <div class="results">
                @forelse ($posts as $post)
                    <div class="result-item">{{ $post->title }}</div>
                @empty
                    <p>No posts found.</p>
                @endforelse
            </div>
        </div>

        <div class="search-category">
            <h3>Users</h3>
            <div class="results">
                @forelse ($users as $user)
                    <div class="result-item">
                        <a href="{{ route('profile.show', ['username' => $user->username]) }}">
                            {{ $user->username }}
                        </a>
                    </div>
                @empty
                    <p>No users found.</p>
                @endforelse
            </div>
        </div>

        <div class="search-category">
            <h3>Comments</h3>
            <div class="results">
                @forelse ($comments as $comment)
                    <div class="result-item">{{ $comment->text }}</div>
                @empty
                    <p>No comments found.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
