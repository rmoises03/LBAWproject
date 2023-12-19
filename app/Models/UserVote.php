<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVote extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'post_id', 'vote_type'];

    // Disable Eloquent's automatic timestamps
    public $timestamps = false;
}
