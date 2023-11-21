<li class="comment" data-id="{{$comment->id}}">
    <label>
        {{--<h4><?= $comment -> user_id ?> </h4>--}}
        <span>{{ $comment->text }}</span>
        <a href="#" class="delete">&#10761;</a>
    </label>
</li>