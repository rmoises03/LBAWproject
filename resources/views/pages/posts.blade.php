@extends('layouts.app') 

@section('content')
    <section id="create_post">
        {{-- Post Creation Form Toggle Button --}}
        <button id="create_new_post_button" onclick="toggleCreatePostForm()">Create New Post</button>

        {{-- Post Creation Form (Initially Hidden) --}}
        <article id="create_new_post_form" class="post" style="display: none;">
            <form method="POST" action="{{ route('post.create') }}">
                @csrf
                <input name="title" type="text" placeholder="Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </article>
    </section>
    
    

    {{-- List of Posts --}}
    <section id="posts">
        @foreach ($posts as $post)
            <article class="post">
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->description }}</p>
                {{-- Delete Button (shown only if authorized) --}}
                @can('delete', $post)
                    <form method="POST" action="{{ route('post.delete', $post->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete Post</button>
                    </form>
                @endcan
            </article>
        @endforeach
    </section>
@endsection
