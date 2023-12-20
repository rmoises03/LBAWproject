<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Tag;


class TagController extends Controller
{
    public function show(Tag $tag) {
        // Fetch posts related to this tag
        // Return view with these posts
        return view('tags.show', compact('tag'));
    }

}