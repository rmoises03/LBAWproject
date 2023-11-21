<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showAdminDashboard()
    {
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }

    public function listUsers()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'Error! Users not found.'], 404);
        }

        return response()->json(['message' => 'Ok. Users retrieved.', 'data' => $users], 200);
    }

    public function toggleAdminStatus(User $user)
    {
        if ($user->isAdmin()->exists()) {
            Admin::where('id', $user->id)->delete();
        } else {
            Admin::create(['id' => $user->id]);
        }

        return redirect()->back()->with('success', 'User status updated.');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);
    
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    
        return redirect()->route('admin.dashboard')
            ->with('success', 'User successfully created.');
    }

    public function blockUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Bad Request. User not found.'], 404);
        }

        if (Block::where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Bad Request. User already blocked.'], 400);
        }

        Block::create(['user_id' => $user->id]);

        return response()->json(['message' => 'Ok. User blocked.'], 204);
    }

    public function unblockUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Bad Request. User not found.'], 404);
        }

        $block = Block::where('user_id', $user->id)->first();

        if (!$block) {
            return response()->json(['message' => 'Bad Request. User already unblocked.'], 400);
        }

        $block->delete();

        return response()->json(['message' => 'Ok. User unblocked.'], 204);
    }
    

}
