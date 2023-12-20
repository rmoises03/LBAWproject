<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use App\Notifications\PostVoted;
use App\Models\UserVote;

use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        // Get the categories.
        $categories = Category::orderBy('id')->get();
        // Get the tags.
        $tags = Tag::orderBy('id')->get();
        // Get the comments for the post.
        $comments = Comment::where('post_id', $id)->orderBy('id')->get();

        if(Auth::check()){
            $user_id = Auth::user()->id;
            $postVote = UserVote::where('user_id', $user_id)->where('post_id', $id)->first();
        }else{
            $postVote = null;
        }

        return view('posts.post_item', [
            'post' => $post,
            'comments' => $comments,
            'postVote' => $postVote,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }


    /**
     * Shows all posts.
     */
    public function listPosts()
    {
        // Get posts for user ordered by id.
        $posts = Post::orderBy('id')->get();
        $categories = Category::orderBy('id')->get();
        $tags = Tag::orderBy('id')->get();

       // Use the pages.posts template to display all posts.
       return view('pages.posts', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags
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
            'category' => 'required|exists:categories,id',
            'tags' => 'array|nullable|exists:tags,id',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        
        $post->user_id = Auth::id(); // Or $request->user()->id
        $post->save();

        $post->categories()->attach($request->category);

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

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
        try {
            $post = Post::findOrFail($post_id);
            $post->increment('upvotes');
            return response()->json(['upvotes' => $post->upvotes]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }


    
    public function downvote_post($post_id){
        $post = Post::findOrFail($post_id);
        $post->increment('downvotes');
        return response()->json(['downvotes' => $post->downvotes]);
    }
    

    public function get_upvotes($post_id){
        $upvotes = Post::findOrFail($post_id)->upvotes;
        return response()->json(['upvotes' => $upvotes]);
    }
    
    public function get_downvotes($post_id){
        $downvotes = Post::findOrFail($post_id)->downvotes;
        return response()->json(['downvotes' => $downvotes]);
    }

    public function vote(Request $request, $post_id, $vote_type){
        try {
            $user_id = $request->user()->id; // Assuming user authentication
            $existingVote = UserVote::where('user_id', $user_id)->where('post_id', $post_id)->first();
    
            $post = Post::findOrFail($post_id);
    
            if ($existingVote) {
                // Check if the user is unvoting
                if ($existingVote->vote_type == $vote_type) {
                    // Unvote logic
                    if ($vote_type == 1) {
                        $post->decrement('upvotes');
                    } else {
                        $post->decrement('downvotes');
                    }
                    $existingVote->delete(); // Delete the vote
                } else {
                    // Change vote type logic
                    if ($vote_type == 1) {
                        $post->increment('upvotes');
                        $post->decrement('downvotes');
                    } else {
                        $post->decrement('upvotes');
                        $post->increment('downvotes');
                    }
                    $existingVote->vote_type = $vote_type;
                    $existingVote->save();
                }
            } else {
                // No existing vote, create a new one
                if ($vote_type == 1) {
                    $post->increment('upvotes');
                } else {
                    $post->increment('downvotes');
                }
    
                UserVote::create([
                    'user_id' => $user_id,
                    'post_id' => $post_id,
                    'vote_type' => $vote_type
                ]);
            }
    
            $post->user->notify(new PostVoted($post));

            return response()->json(['upvotes' => $post->upvotes, 'downvotes' => $post->downvotes]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
    
    
    public function update(Request $request, $id)
    {
        #$this->authorize('update', Post::class);

        if ($request->tags === [null]) {
            $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'category' => 'required|exists:categories,id',
            ]);

            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->description = $request->description;
            $post->save();

            $post->categories()->sync($request->category);
            $post->tags()->sync([]);
        }
        else {
            $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'category' => 'required|exists:categories,id',
                'tags' => 'array|nullable|exists:tags,id',
            ]);
        
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->description = $request->description;
            $post->save();

            $post->categories()->sync($request->category);
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('post.open', ['id' => $id]);
    }

    
}
