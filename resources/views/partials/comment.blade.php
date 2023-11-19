<li class="comment" data-id="{{$comment->id}}">
    <label>
        <input type="checkbox" {{ $comment->done?'checked':''}}>
        <span>{{ $comment->description }}</span>
        <a href="#" class="delete">&#10761;</a>
    </label>
</li>