{{-- resources/views/admin/users.blade.php --}}

@extends('layouts.app')

@section('title', 'Admin - List Users')

@section('content')
    <div style="max-height: 400px; overflow-y: auto;">
        @foreach ($users as $user)
            <div class="user-item">
                {{ $user->name }} - {{ $user->isAdmin()->exists() ? 'Admin' : 'User' }}
                <form method="POST" action="{{ route('admin.toggleAdmin', $user) }}">
                    @csrf
                    <button type="submit" class="toggle-button">
                        {{ $user->isAdmin()->exists() ? 'Remove Admin' : 'Make Admin' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
