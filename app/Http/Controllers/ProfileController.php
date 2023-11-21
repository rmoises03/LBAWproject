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
        $user = User::where('username', $username)->firstOrFail();

        return view('profiles.edit', compact('user'));
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
