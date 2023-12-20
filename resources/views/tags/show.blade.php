{{-- tags/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Posts with Tag: {{ $tag->name }}</h1>
    @foreach ($tag->posts as $post)
        <div>
            <a href="{{ route('post.open', ['id' => $post->id]) }}">
            {{ $post->title }}
            </a>
        </div>
    @endforeach
@endsection
