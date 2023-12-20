<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;


class CategoryController extends Controller
{
    public function show(Category $category) {
        // Fetch posts related to this category
        // Return view with these posts
        return view('categories.show', compact('category'));
    }

}