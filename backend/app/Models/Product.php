<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category_id', 'description', 'image', 'price', 'unit', 'specs', 'status',
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

    /**
     * 檢查產品是否可以在前台顯示
     */
    public function isVisible()
    {
        return in_array($this->status, ['published', 'notification']);
    }

    /**
     * 檢查產品是否可以加入購物車
     */
    public function canAddToCart()
    {
        return $this->status === 'published';
    }

    /**
     * 取得狀態文字
     */
    public function getStatusTextAttribute()
    {
        $statusMap = [
            'draft' => '草稿',
            'published' => '上架',
            'notification' => '通知',
            'archived' => '封存'
        ];
        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * 取得狀態顏色
     */
    public function getStatusColorAttribute()
    {
        $colorMap = [
            'draft' => 'gray',
            'published' => 'green',
            'notification' => 'blue',
            'archived' => 'red'
        ];
        return $colorMap[$this->status] ?? 'gray';
    }
} 