<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'content', 'images', 'images_public_ids', 'videos', 'videos_public_ids', 'status', 'published_at', 'user_id'
    ];

    protected $casts = [
        'images' => 'array',
        'images_public_ids' => 'array',
        'videos' => 'array',
        'videos_public_ids' => 'array',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
