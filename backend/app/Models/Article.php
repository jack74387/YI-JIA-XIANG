<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'content', 'images', 'videos', 'status', 'published_at', 'user_id'
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
