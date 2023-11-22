<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Block;
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

        $defaultDateOfBirth = Carbon::create(2000, 1, 1);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $defaultDateOfBirth 
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'User successfully created.');
    }


    public function blockUser(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'reason' => 'required|string|max:255',
    ]);

    $adminId = Auth::id(); // Assuming you have the admin ID available

    Block::create([
        'admin_id' => $adminId,
        'user_id' => $request->user_id,
        'blocked_at' => now(),
        'reason' => $request->reason
    ]);

    return redirect()->back()->with('success', 'User blocked successfully.');
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

        return redirect()->back()->with('success', 'User unblocked successfully.');
    }
    

}
