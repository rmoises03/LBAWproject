<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Creates a new comment.
     */
    public function create(Request $request, $post_id)
    {
        // Create a blank new comment.
        $comment = new Comment();

        // Set comment's post.
        $comment->post_id = $post_id;

        // Check if the current user is authorized to create this comment.
        $this->authorize('create', $comment);

        // Set comment details.
        $comment->done = false;
        $comment->description = $request->input('description');

        // Save the comment and return it as JSON.
        $comment->save();
        return response()->json($comment);
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