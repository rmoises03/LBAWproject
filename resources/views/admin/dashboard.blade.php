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

                @if($user->isBlocked())
                <button onclick="openUnblockOverlay({{ $user->id }})">Unblock User</button>
            @else
                <button onclick="openBlockOverlay({{ $user->id }})">Block User</button>
            @endif
            </div>
        @endforeach
    </div>

    <!-- Block User Overlay -->
    <div id="blockUserOverlay" class="overlay" style="display: none;">
        <div class="overlay-content">
            <span class="close-button" onclick="closeBlockOverlay()">&times;</span>
            <form method="POST" action="{{ route('admin.blockUser') }}">
                @csrf
                <input type="hidden" id="block_user_id" name="user_id">
                <label for="reason">Reason for Blocking:</label>
                <textarea id="reason" name="reason" required></textarea><br>
                <button type="submit">Block User</button>
            </form>
        </div>
    </div>

    <div id="unblockUserOverlay" class="overlay" style="display: none;">
        <div class="overlay-content">
            <span class="close-button" onclick="closeUnblockOverlay()">&times;</span>
            <form method="POST">
                @csrf
                <input type="hidden" id="unblock_user_id" name="user_id">
                <button type="submit">Unblock User</button>
            </form>
        </div>
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

        function openBlockOverlay(userId) {
            document.getElementById('block_user_id').value = userId;
            document.getElementById('blockUserOverlay').style.display = 'block';
        }

        function openUnblockOverlay(userId) {
            document.getElementById('unblock_user_id').value = userId;
            document.getElementById('unblockUserOverlay').style.display = 'block';

            // Set the form's action attribute
            document.getElementById('unblockUserForm').action = `/admin/users/unblock/${userId}`;
        }


        function closeBlockOverlay() {
            document.getElementById('blockUserOverlay').style.display = 'none';
        }

        function closeUnblockOverlay() {
            document.getElementById('unblockUserOverlay').style.display = 'none';
        }


    </script>
    
@endsection
