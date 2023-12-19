<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostCommented;
use App\Notifications\CommentReplied;


class CommentController extends Controller
{
    /**
     * Creates a new comment.
     */
    public function create(Request $request, $post_id, $parent_comment_id)
    {
        // Create a new comment.
        $comment = new Comment();
    
        // Set the comment's post.
        $comment->post_id = $post_id;
    
        // Set the parent comment ID, if provided.
        $comment->parent_comment_id = $parent_comment_id == '0' ? null : $parent_comment_id;
        $comment->user_id = Auth::id();
        $comment->text = $request->input('comment');
    
        // Save the comment.
        $comment->save();
    
        // Retrieve the post and all related comments.
        $post = Post::with('comments')->findOrFail($post_id);
    
        if ($parent_comment_id == '0') {
            // This is a root comment, so notify the post's author.
            $post->user->notify(new PostCommented($post, $comment));
        } else {
            // This is a reply, so notify the parent comment's author.
            $parent_comment = Comment::find($parent_comment_id);
            $parent_comment->user->notify(new CommentReplied($post, $comment, $parent_comment));
        }

        // Redirect to the post with the new comment.
        return redirect()->route('post.open', $post->id)->with('status', 'Comment added successfully!');
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

    public function create_child(Request $request, $post_id, $parent_comment_id)
    {
        $comment = new Comment();

        // Redirect to the post with an anchor to the new comment
        return redirect()->route('post.open', $post_id) . '#comment-' . $comment->id;
    }

    

    
}
