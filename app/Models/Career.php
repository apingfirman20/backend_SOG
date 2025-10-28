<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = 'career';

    protected $fillable = [
        'position',
        'company',
        'salary',
        'update_date',
    ];
}
