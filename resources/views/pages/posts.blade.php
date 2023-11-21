@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<section id="posts">
    <article id="create_new_post">
        <form class="new_post">
            <input type="text" name="name" placeholder="new post">
        </form>
    </article>
    @each('partials.post', $posts, 'post')
</section>

@endsection