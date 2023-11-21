<article class="post" data-id="{{ $post->id }}">
    <header>
        {{--<h4>$users -> username ($post -> user_id)</h4>--}}
        <h2><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <p>{{ $post->description }}</p>
    <div class="votes"> <?= $post->upvotes  -  $post->downvotes ;?></div>
    <ul>
        @each('partials.comment', $post->comments()->orderBy('id')->get(), 'comment')
    </ul>
    <form class="new_comment">
        <input type="text" name="description" placeholder="new comment">
    </form>
</article>