<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coments extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'name',
        'email',
        'comment',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
