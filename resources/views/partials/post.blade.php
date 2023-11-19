<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2><a href="/posts/{{ $post->id }}">{{ $post->name }}</a></h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <ul>
        @each('partials.comment', $post->comments()->orderBy('id')->get(), 'comment')
    </ul>
    <form class="new_comment">
        <input type="text" name="description" placeholder="new comment">
    </form>
</article>