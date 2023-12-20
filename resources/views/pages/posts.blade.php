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
                @if ($errors->has('title'))
                    <span class="error">
                        {{ $errors->first('title') }}
                    </span>
                @endif
                <textarea name="description" placeholder="Description" required></textarea>
                @if ($errors->has('description'))
                    <span class="error">
                        {{ $errors->first('description') }}
                    </span>
                @endif

                <label for="category">Category:</label>
                <select name="category" required>
                    <option value="" disabled selected>Select a Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('category'))
                    <span class="error">
                        {{ $errors->first('category') }}
                    </span>
                @endif

                <label for="tags">Tags:</label>
                <select name="tags[]" multiple>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('tags'))
                    <span class="error">
                        {{ $errors->first('tags') }}
                    </span>
                @endif

                <button type="submit">Submit</button>
            </form>
        </article>
    </section>
    
    

    {{-- List of Posts --}}
    <section id="posts">
        @foreach ($posts as $post)
            <article class="post">
                <a href="{{ route('post.open', ['id' => $post->id]) }}">
                    <h2>{{ $post->title }}</h2>
                </a>
                <p>{{ \Illuminate\Support\Str::limit($post->description, 250, '...') }}</p>
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
