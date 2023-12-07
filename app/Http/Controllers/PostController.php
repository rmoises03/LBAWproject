<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

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
     * Show the post for a given id.
     */
    public function open(string $id): View
    {
        // Get the post.
        $post = Post::findOrFail($id);
        $comments = Comment::where('post_id', $id)->orderBy('id')->get();
        return view('posts.post_item', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Shows all posts.
     */
    public function listPosts()
    {
        // Get posts for user ordered by id.
        $posts = Post::orderBy('id')->get();

        // Use the pages.posts template to display all posts.
        return view('pages.posts', [
            'posts' => $posts
        ]);
    }

    /**
     * Shows all posts of user.
     */
    public function listUserPosts()
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
            return view('pages.profile', [
                'posts' => $posts
            ]);
        }
    }

    /**
     * Creates a new post.
     */
        public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = Auth::id(); // Or $request->user()->id
        $post->save();

        return redirect()->route('posts');
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('delete', $post);

        $post->delete();
        return redirect()->route('posts');
    }


    public function upvote_post($post_id){
        $post = Post::findOrFail($post_id);
        $post->upvotes += 1;
    }

    public function downvote_post($post_id){
        $post = Post::findOrFail($post_id);
        $post->downvotes += 1;
    }
}
