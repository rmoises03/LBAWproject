<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class SearchController extends Controller
{
    public function global_search(Request $request)
    {
        $query = $request->input('query');
        $ftsQuery = pg_escape_string($query); // Properly escape the query string for FTS

        // Full-Text Search in posts (for title and description)
        $posts = Post::whereRaw("tsv @@ to_tsquery('english', ?)", [$ftsQuery])->get();

        // Full-Text Search in comments (for comment text)
        $comments = Comment::whereRaw("tsv @@ to_tsquery('english', ?)", [$ftsQuery])->get();

        // Trigram search in users (for name and username)
        // using ILIKE for partial and case-insensitive matching
        $users = User::whereRaw("name ILIKE ? OR username ILIKE ?", ['%' . $query . '%', '%' . $query . '%'])->get();

        return view('search.results', compact('posts', 'users', 'comments'));
    }
}
