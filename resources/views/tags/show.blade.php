{{-- tags/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Posts with Tag: {{ $tag->name }}</h1>
    @foreach ($tag->posts as $post)
        <div>{{ $post->title }}</div>
        {{-- Add more post details here --}}
    @endforeach
@endsection
