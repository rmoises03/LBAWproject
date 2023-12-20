{{-- categories/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Posts in Category: {{ $category->name }}</h1>
    @foreach ($category->posts as $post)
        <div>
            <a href="{{ route('post.open', ['id' => $post->id]) }}">
            {{ $post->title }}
            </a>
        </div>
    @endforeach
@endsection
