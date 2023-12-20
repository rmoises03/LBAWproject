<div class="comment-item depth-{{ $depth }}" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <a href="{{ route('profile.show', ['username' => $comment->user->username]) }}" class="user-link">{{ $comment->user->username }}</a>
        <span class="comment-date">{{ $comment->created_at }}</span>
    </div>
    <div class="comment-body">
        <p>{{ $comment->text }}</p>
    </div>
    <div class="post-flex-container">
        <div class="post-data">
            <button onclick="voteComment({{ $comment->id }}, 1)" id="upvote-button-{{ $comment->id }}" class="bi bi-arrow-up"></button>
            <span id="upvotes-count-{{ $comment->id }}">{{ $comment->upvotes }}</span>

            <button onclick="voteComment({{ $comment->id }}, -1)" id="downvote-button-{{ $comment->id }}" class="bi bi-arrow-down"></button>
            <span id="downvotes-count-{{ $comment->id }}">{{ $comment->downvotes }}</span>
        </div>
    </div>

    <!-- Reply Form -->
    <form method="POST" action="{{ route('comment.create', ['post_id' => $comment->post_id, 'parent_comment_id' => $comment->id]) }}">
        @csrf
        <textarea name="comment" required placeholder="Reply to this comment"></textarea><br>
        <button type="submit">Reply</button>
    </form>
    

    @if (Auth::check() && Auth::user()->id == $comment->user_id)
        <div class="post-data">
            <!-- Button to open the edit comment overlay -->
            <button type="button" onclick="openEditCommentOverlay('{{ route('comment.update', $comment->id) }}', '{{ $comment->text }}', '{{ $comment->id }}')">Edit Comment</button>


            <!-- Button to open the delete comment overlay -->
            <!-- The delete button can be a form to send a DELETE request -->
            <button type="button" onclick="openDeleteCommentOverlay('{{ route('comment.delete', $comment->id) }}')">Delete Comment</button>
        </div>

        <div id="deleteCommentOverlay" class="overlay" style="display: none;">
            <div class="overlay-content">
                <span class="close-button" onclick="closeDeleteCommentOverlay()">&times;</span>
                <h4>Are you sure you want to delete this comment?</h4>
                <p>This action <strong>cannot</strong> be undone.</p>
                <form id="deleteCommentForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button">Delete Comment</button>
                    <button type="button" onclick="closeDeleteCommentOverlay()">Cancel</button>
                </form>
            </div>
        </div>
        
        <div id="editCommentOverlay" class="overlay" style="display: none;">
            <div class="overlay-content">
                <span class="close-button" onclick="closeEditCommentOverlay()">&times;</span>
                <form id="editCommentForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <label for="comment">Comment:</label>
                    <textarea id="edit_comment" name="comment" required>{{$comment->text }}</textarea>
                    <button type="submit" class="button">Update Comment</button>
                    <button type="button" onclick="closeEditCommentOverlay()">Cancel</button>
                </form>
            </div>
        </div>

    @endif


    <!-- Nested Comments -->
    @if($comment->replies)
        @foreach($comment->replies as $reply)
            @include('posts.comment', ['comment' => $reply, 'depth' => $depth + 1])
        @endforeach
    @endif
</div>




