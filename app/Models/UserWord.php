<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWord extends Model
{
    protected $table = 'user_words';

    protected $fillable = [
        'user_id',
        'word',
    ];

    public $timestamps = false;

}
