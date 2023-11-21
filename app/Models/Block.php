<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $table = 'blocks';

    protected $fillable = ['admin_id', 'user_id', 'blocked_at', 'reason'];

    public $timestamps = false; 

    

}
