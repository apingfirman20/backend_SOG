<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coments extends Model
{
    use HasFactory;

    protected $table = 'coments';
    protected $fillable = [
        'news_id',
        'name',
        'comment',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
