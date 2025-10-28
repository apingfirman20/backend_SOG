<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;
    public function media()
    {
        return $this->belongsTo(MediaFile::class, 'media_id');
    }

    protected $fillable = [
        'title',
        'deskripsi',
        'company',
        'image',
    ];
}
