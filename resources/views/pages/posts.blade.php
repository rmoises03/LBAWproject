@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<section id="posts">
    <article id="create_new_post">
        <button class="create_new_post" onclick="showCreatePostForm()">Create New Post</button>
        <div id="create_post_form" style="display: none;">
            <form id="newPostForm">
                <div id="input_container"></div>
                <input name="title" type="Text" placeholder="Title" required></input>
                <textarea name="description" type="Text" placeholder="Description" required></textarea>
                <input id="user_id" name="user_id" type="hidden" value="{{ Auth::user()->id }}"></input>
                <button type="submit" onsubmit="sendCreatePostRequest(event)">Submit</button>
            </form>
    </article>
    @each('partials.post', $posts, 'post')
</section>

@endsection