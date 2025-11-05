<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;
    public function mediaFiles()
{
    return $this->hasMany(MediaFile::class, 'news_id');
}
public function comments()
{
    return $this->hasMany(Coments::class, 'news_id');
}




    protected $fillable = [
        'title',
        'deskripsi',
        'company',
        'image',
    ];
}
