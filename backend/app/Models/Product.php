<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category_id', 'description', 'image', 'price', 'unit', 'specs',
        'price_large', 'price_small', 'hot', 'views', 'subtitle', 'rating', 'rating_count', 'sold_count', 'stock',
        'images', 'spec', 'tags', 'recommend_ids', 'delivery', 'payment', 'origin_price', 'weight', 'share_links'
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'recommend_ids' => 'array',
        'delivery' => 'array',
        'payment' => 'array',
        'share_links' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function getPrimaryImageAttribute()
    {
        if (is_array($this->images) && count($this->images) > 0) {
            return (object)[
                'image_path' => $this->images[0]['image_path'] ?? $this->images[0] ?? $this->image
            ];
        }
        return (object)[
            'image_path' => $this->image
        ];
    }
} 