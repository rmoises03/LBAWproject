@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<section id="posts">
    <article id="create_new_post">
        <button class=create_new_post></button>
    </article>
    @each('partials.post', $posts, 'post')
</section>

@endsection