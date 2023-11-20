{{-- resources/views/admin/admin_dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h2>Admin Dashboard</h2>
    <p>Welcome to your dashboard, {{ Auth::user()->name }}!</p>
    <form method="POST" action="{{ url('/admin/NIP') }}">
        @csrf
        {{-- Form content here --}}
        <button type="submit">Submit</button>
    </form>
@endsection
