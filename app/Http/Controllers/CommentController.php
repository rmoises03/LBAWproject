<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Post;


class CommentController extends Controller
{

    /**
     * Creates a new comment.
     */
    public function create(Request $request, $post_id,$parent_comment_id)
    {
        // Create a blank new comment.
        $comment = new Comment();

        // Set comment's post.
        $comment->post_id = $post_id;

    
        $comment->parent_comment_id = NULL;
        $comment->user_id = Auth::id();
        $comment->text = $request->input('comment');

        // Save the comment and return it as JSON.
        $comment->save();
        $post = Post::findOrFail($post_id);
        $comments = Comment::where('post_id', $post_id)->orderBy('id')->get();
        return view('posts.post_item', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Updates the state of an individual comment.
     */
    public function update(Request $request, $id)
    {
        // Find the comment.
        $comment = Comment::find($id);

        // Check if the current user is authorized to update this comment.
        $this->authorize('update', $comment);

        // Update the done property of the comment.
        $comment->done = $request->input('done');

        // Save the comment and return it as JSON.
        $comment->save();
        return response()->json($comment);
    }

    /**
     * Deletes a specific comment.
     */
    public function delete(Request $request, $id)
    {
        // Find the comment.
        $comment = Comment::find($id);

        // Check if the current user is authorized to delete this comment.
        $this->authorize('delete', $comment);

        // Delete the comment and return it as JSON.
        $comment->delete();
        return response()->json($comment);
    }

    
}
