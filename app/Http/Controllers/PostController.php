<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Show the post for a given id.
     */
    public function show(string $id): View
    {
        // Get the post.
        $post = Post::findOrFail($id);

        // Check if the current user can see (show) the post.
        $this->authorize('show', $post);  

        // Use the pages.post template to display the post.
        return view('pages.post', [
            'post' => $post
        ]);
    }

    /**
     * Shows all posts.
     */
    public function list()
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Get posts for user ordered by id.
            $posts = Auth::user()->posts()->orderBy('id')->get();

            // Check if the current user can list the posts.
            $this->authorize('list', Post::class);

            // The current user is authorized to list posts.

            // Use the pages.posts template to display all posts.
            return view('pages.posts', [
                'posts' => $posts
            ]);
        }
    }

    /**
     * Creates a new post.
     */
    public function create(Request $request)
    {
        // Create a blank new Post.
        $post = new Post();

        // Check if the current user is authorized to create this post.
        $this->authorize('create', $post);

        // Set post details.
        $post->name = $request->input('name');
        $post->user_id = Auth::user()->id;

        // Save the post and return it as JSON.
        $post->save();
        return response()->json($post);
    }

    /**
     * Delete a post.
     */
    public function delete(Request $request, $id)
    {
        // Find the post.
        $post = Post::find($id);

        // Check if the current user is authorized to delete this post.
        $this->authorize('delete', $post);

        // Delete the post and return it as JSON.
        $post->delete();
        return response()->json($post);
    }
}
