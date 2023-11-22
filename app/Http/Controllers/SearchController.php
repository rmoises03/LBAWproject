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

    // Search in posts using pg_trgm
    $posts = Post::whereRaw("title || ' ' || description ILIKE ?", ['%' . $query . '%'])->get();

    // Search in users using pg_trgm
    $users = User::whereRaw("email ILIKE ? OR username ILIKE ?", ['%' . $query . '%', '%' . $query . '%'])->get();

    // Search in comments using pg_trgm
    $comments = Comment::whereRaw("text ILIKE ?", ['%' . $query . '%'])->get();

    return view('search.results', compact('posts', 'users', 'comments'));
}

}
