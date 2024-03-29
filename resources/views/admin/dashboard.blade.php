{{-- resources/views/admin/admin_dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h2>Dashboard</h2>

    <div class="user-section">
        <div class="section-wrapper">
            <h3>Users</h3>
            <div class="user-list">
                @foreach ($users as $user)
                    <div class="user-item">
                        <a href="{{ route('profile.show', ['username' => $user->username]) }}" class="user-link">{{ $user->username }}</a>
                        - {{ $user->isAdmin()->exists() ? 'admin' : 'user' }}
        
                        <form method="POST" action="{{ route('admin.toggleAdmin', $user) }}" class="admin-form">
                            @csrf
                            <button type="submit" class="toggle-button">
                                {{ $user->isAdmin()->exists() ? 'Remove Admin' : 'Make Admin' }}
                            </button>
                        </form>
        
                        @if($user->isBlocked())
                            <button onclick="openUnblockOverlay({{ $user->id }})" class="block-button">Unblock User</button>
                        @else
                            <button onclick="openBlockOverlay({{ $user->id }})" class="block-button">Block User</button>
                        @endif
                    </div>
                @endforeach
            </div>
            <button id="addUserButton">Add New User</button>
        </div>
       
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
            <form id="unblockUserForm" method="POST">
                @csrf
                <input type="hidden" id="unblock_user_id" name="user_id">
                <button type="submit">Unblock User</button>
            </form>
        </div>
    </div>
    
    



    

    <div id="addUserOverlay" class="overlay">
        <div class="overlay-content">
            <span class="close-button" onclick="closeNewUserOverlay()">&times;</span>
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
        function openNewUserOverlay() {
            document.getElementById("addUserOverlay").style.display = "block";
        }
    
        function closeNewUserOverlay() {
            document.getElementById("addUserOverlay").style.display = "none";
        }
    
        document.getElementById("addUserButton").onclick = openNewUserOverlay;

        function openBlockOverlay(userId) {
            document.getElementById('block_user_id').value = userId;
            document.getElementById('blockUserOverlay').style.display = 'block';
        }

        function openUnblockOverlay(userId) {
            document.getElementById('unblock_user_id').value = userId;
            document.getElementById('unblockUserOverlay').style.display = 'block';

            // Set the form's action attribute
            document.getElementById('unblockUserForm').action = `/admin/users/unblock/${userId}`;
            form.action = `/admin/users/unblock/${userId}`;
        }


        function closeBlockOverlay() {
            document.getElementById('blockUserOverlay').style.display = 'none';
        }

        function closeUnblockOverlay() {
            document.getElementById('unblockUserOverlay').style.display = 'none';
        }


    </script>
    
@endsection
