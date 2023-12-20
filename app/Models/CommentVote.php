<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    // If you're not following Laravel's naming conventions, specify the table name
    protected $table = 'comment_votes';

    // Fillable fields to protect against mass-assignment
    protected $fillable = ['user_id', 'comment_id', 'vote_type'];

    // Disable timestamps if you don't have them in your table
    public $timestamps = false;

    // Define relationship to User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Define relationship to Comment
    public function comment() {
        return $this->belongsTo(Comment::class);
    }
}
