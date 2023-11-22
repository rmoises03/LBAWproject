<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Console\View\Components\Alert;

class ProfileController extends Controller
{
    /**
     * Show the profile for a given user by username.
     */
    public function show($username)
    {
        if (!Auth::check()) {
            return redirect('/login');
        } else {
            $user = User::with('posts')->where('username', $username)->firstOrFail();
            return view('profiles.show', [
                'user' => $user
            ]);
        }
    }


    public function edit($username)
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Fetch the user based on the username
            $user = User::where('username', $username)->firstOrFail();
            
            // Check if the current user can edit the profile.
            //$this->authorize('edit', $user);

            // Pass the user data to the view
            return view('profiles.edit', [
                'user' => $user
            ]);
        }
    }

    public function update(Request $request, $username)
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Fetch the user based on the username
            $user = User::where('username', $username)->firstOrFail();

            // Check if the current user can update the profile.
            //$this->authorize('updateProfile', $user);


            // Validate the form data
            $validatedData = $request->validate([
                'username' => 'required|string|max:250|unique:users,username,'.$user->id,
                'date_of_birth' => 'required|date',
                'reputation' => 'required|numeric',
            ]);

            // Update the user's profile information
            $user->update($validatedData);

            return redirect()->route('profile.show', ['username' => $user->username])
                ->with('success', 'Profile updated successfully');
        }
    }

}
