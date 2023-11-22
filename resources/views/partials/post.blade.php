<article class="post" data-id="{{ $post->id }}">
    <header>
        {{--<h4>$users -> username ($post -> user_id)</h4>--}}
        <h2><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <p>{{ $post->description }}</p>
    <div class="votes"><a href="#" class="upvote">+</a> <?= $post->upvotes  -  $post->downvotes ;?> <a href="#" class="downvote">-</a></div> </div>        
    <h5 class="comments">Comments</h5>
    <ul>
        @each('partials.comment', $post->comments()->orderBy('id')->get(), 'comment')
    </ul>
    <form class="new_comment">
        <input type="text" name="description" placeholder="new comment">
    </form>
</article>