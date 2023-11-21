{{-- resources/views/admin/admin_dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h2>Dashboard</h2>

   

    <h3>Users</h3>
    <div style="max-height: 400px; overflow-y: auto;">
        @foreach ($users as $user)
            <div class="user-item">
                {{ $user->username}} - {{ $user->isAdmin()->exists() ? 'admin' : 'user' }}
                <form method="POST" action="{{ route('admin.toggleAdmin', $user) }}">
                    @csrf
                    <button type="submit" class="toggle-button">
                        {{ $user->isAdmin()->exists() ? 'Remove Admin' : 'Make Admin' }}
                    </button>
                </form>

                <!-- Block/Unblock Button -->
            <form method="POST" action="{{ $user->isBlocked() ? route('admin.unblockUser', $user) : route('admin.blockUser', $user) }}">
                @csrf
                <button type="submit" class="toggle-button">
                    {{ $user->isBlocked() ? 'Unblock User' : 'Block User' }}
                </button>
            </form>
            </div>
        @endforeach
    </div>

    <button id="addUserButton">Add New User</button>

    <div id="addUserOverlay" class="overlay">
        <div class="overlay-content">
            <span class="close-button" onclick="closeOverlay()">&times;</span>
            <form method="POST" action="{{ route('admin.createUser') }}">
                @csrf
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>


                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required>

                <button type="submit">Create User</button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </form>
        </div>
    </div>



    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: white;
            border-radius: 5px;
            text-align: center;
        }

        .close-button {
            float: right;
            font-size: 28px;
            cursor: pointer;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #fff;
        }

        .alert-success {
            background-color: #28a745;
        }

        .alert-danger {
            background-color: #dc3545;
        }


    </style>
    <script>
        function openOverlay() {
            document.getElementById("addUserOverlay").style.display = "block";
        }
    
        function closeOverlay() {
            document.getElementById("addUserOverlay").style.display = "none";
        }
    
        document.getElementById("addUserButton").onclick = openOverlay;
    </script>
    
@endsection
