<div class="comment-item depth-{{ $depth }}" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <a href="{{ route('profile.show', ['username' => $comment->user->username]) }}" class="user-link">{{ $comment->user->username }}</a>
        <span class="comment-date">{{ $comment->created_at }}</span>
    </div>
    <div class="comment-body">
        <p>{{ $comment->text }}</p>
    </div>

    <!-- Reply Form -->
    <form method="POST" action="{{ route('comment.create', ['post_id' => $comment->post_id, 'parent_comment_id' => $comment->id]) }}">
        @csrf
        <textarea name="comment" required placeholder="Reply to this comment"></textarea><br>
        @if ($errors->has('comment'))
            <span class="error">{{ $errors->first('comment') }}</span>
        @endif
        <button type="submit">Reply</button>
    </form>

    <!-- Nested Comments -->
    @if($comment->replies)
        @foreach($comment->replies as $reply)
            @include('posts.comment', ['comment' => $reply, 'depth' => $depth + 1])
        @endforeach
    @endif
</div>
