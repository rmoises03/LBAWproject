<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the user where the comment is included.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post where the comment is included.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_comment_id');
    }
}
