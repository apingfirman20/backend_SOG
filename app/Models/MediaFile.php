<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MediaFile extends Model
{
        use HasFactory;
public function news()
{
    return $this->belongsTo(News::class, 'news_id');
}



    protected $fillable = [
        'news_id',
        'filename',
        'file_path',
        'type',
    ];
}
