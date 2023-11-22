<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        // Search in posts
        $posts = Post::where('title', 'like', '%' . $query . '%')
                     ->orWhere('description', 'like', '%' . $query . '%')
                     ->get();

        // Search in users
        $users = User::where('name', 'like', '%' . $query . '%')
                     ->orWhere('username', 'like', '%' . $query . '%')
                     ->get();

        // Search in comments
        $comments = Comment::where('text', 'like', '%' . $query . '%')->get();

        return view('search.results', compact('posts', 'users', 'comments'));
    }
}
