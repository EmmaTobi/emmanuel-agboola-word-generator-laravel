<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    const NUMBER_MAX = 9;
    const NUMBER_MIN = 0;
    const LOWERCASE_MAX = 35;
    const LOWERCASE_MIN = 10;
    const UPPERCASE_MAX = 61;
    const UPPERCASE_MIN = 36;

    const ALPHABET = 'alphabet';
    const NUMBER = 'number';
    const ALPHA_NUMERIC = 'alphanumeric';

    protected $table = 'characters';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'symbol',
    ];

}
