<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{

    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        return view('profiles.show', compact('user'));
    }

    public function edit($username)
    {
        // Fetch the user based on the username
        $user = User::where('username', $username)->first();
    
        // Check if the user exists
        if (!$user) {
            abort(404); // Or handle the case when the user is not found
        }
    
        // Pass the user data to the view
        return view('profiles.edit', ['user' => $user]);
    }

    public function update(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // Validate the form data
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'date_of_birth' => 'required|date',
            'reputation' => 'required|numeric',
        ]);

        // Update the user's profile information
        $user->update($validatedData);

        return redirect()->route('profile.show', ['username' => $user->username])
            ->with('success', 'Profile updated successfully');
    }
}
