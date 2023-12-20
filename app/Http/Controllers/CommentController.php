<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Comment;
use App\Models\Post;
use App\Models\CommentVote;
use App\Notifications\PostCommented;
use App\Notifications\CommentReplied;
use App\Notifications\CommentVoted;


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
     * Updates the text of an individual comment.
     */
    public function update(Request $request, $id)
    {
        // Find the comment.
        $comment = Comment::find($id);

        // Check if the current user is authorized to update this comment.
        $this->authorize('update', $comment);

        // Update the comment's text.
        $comment->text = $request->input('comment');

        // Save the comment.
        $comment->save();

        // Redirect back to the post with a success message.
        return redirect()->route('post.open', $comment->post_id)
                        ->with('status', 'Comment updated successfully!');
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

        // Store the post ID before deleting the comment.
        $post_id = $comment->post_id;

        // Delete the comment.
        $comment->delete();

        // Redirect back to the post with a success message.
        return redirect()->route('post.open', $post_id)
                        ->with('status', 'Comment deleted successfully!');
    }


    public function create_child(Request $request, $post_id, $parent_comment_id)
    {
        $comment = new Comment();

        // Redirect to the post with an anchor to the new comment
        return redirect()->route('post.open', $post_id) . '#comment-' . $comment->id;
    }

    public function vote(Request $request, $comment_id, $vote_type){
        try {
            $user_id = Auth::id();
            $existingVote = CommentVote::where('user_id', $user_id)
                                       ->where('comment_id', $comment_id)
                                       ->first();
    
            $comment = Comment::findOrFail($comment_id);
    
            if ($existingVote) {
                if ($existingVote->vote_type == $vote_type) {
                    // Removing the vote
                    if ($vote_type == 1) {
                        $comment->decrement('upvotes');
                    } else {
                        $comment->decrement('downvotes');
                    }
                    $existingVote->delete();
                } else {
                    // Change vote type
                    if ($vote_type == 1) {
                        $comment->increment('upvotes');
                        $comment->decrement('downvotes');
                    } else {
                        $comment->decrement('upvotes');
                        $comment->increment('downvotes');
                    }
                    $existingVote->vote_type = $vote_type;
                    $existingVote->save();
                }
            } else {
                // No existing vote, create a new one
                if ($vote_type == 1) {
                    $comment->increment('upvotes');
                } else {
                    $comment->increment('downvotes');
                }
    
                CommentVote::create([
                    'user_id' => $user_id,
                    'comment_id' => $comment_id,
                    'vote_type' => $vote_type
                ]);
            }

            $post = $comment->post;

            $comment->user->notify(new CommentVoted($post, $comment));

            return response()->json(['upvotes' => $comment->upvotes, 'downvotes' => $comment->downvotes]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Comment not found'], 404);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
    

    private function updateCommentVoteCounts(Comment $comment, $voteChange) {
        if ($voteChange == 1) {
            $comment->increment('upvotes');
        } elseif ($voteChange == -1) {
            $comment->increment('downvotes');
        } elseif ($voteChange == 2) {
            $comment->increment('upvotes');
            $comment->decrement('downvotes');
        } elseif ($voteChange == -2) {
            $comment->decrement('upvotes');
            $comment->increment('downvotes');
        }
    }

    

    
}
