@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<section id="posts">
    <article id="create_new_post">
        <button class="create_new_post" onclick="showCreatePostForm()">Create New Post</button>
        <div id="create_post_form" style="display: none;">
            <form id="newPostForm">
                <div id="input_container"></div>
                <textarea name="title" placeholder="Title"></textarea>
                <textarea name="description" placeholder="Description"></textarea>
                <button type="submit" onclick="sendCreatePostRequest(event)">Submit</button>
            </form>
    </article>
    @each('partials.post', $posts, 'post')
</section>

@endsection