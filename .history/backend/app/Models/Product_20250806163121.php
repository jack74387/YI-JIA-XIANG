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
        'images', 'spec', 'tags', 'recommend_ids', 'delivery', 'payment', 'origin_price', 'weight', 'share_links',
        // 新增的營養和成分資訊欄位
        'nutrition_info', 'ingredients', 'allergens', 'shelf_life', 'storage_instructions', 'origin', 'package_info',
        // 精選商品相關欄位
        'is_featured', 'featured_order'
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'recommend_ids' => 'array',
        'delivery' => 'array',
        'payment' => 'array',
        'share_links' => 'array',
        // 新增的 JSON 欄位
        'nutrition_info' => 'array',
        'package_info' => 'array',
        // 布林值轉換
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function getPrimaryImageAttribute()
    {
        // 優先使用 image 欄位（後台上傳的主圖）
        if ($this->image) {
            $img = $this->image;
            
            // 如果是完整的 URL（如 Cloudinary），直接返回
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return (object)[
                    'image_path' => $img
                ];
            }
            
            // 統一路徑格式
            $img = str_replace('', '/', $img);
            if (!str_starts_with($img, '/')) {
                $img = '/' . ltrim($img, '/');
            }
            return (object)[
                'image_path' => $img
            ];
        }
        // 如果沒有 image，則使用 images 陣列的第一張
        if (is_array($this->images) && count($this->images) > 0) {
            $img = $this->images[0]['image_path'] ?? $this->images[0];
            
            // 如果是完整的 URL（如 Cloudinary），直接返回
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return (object)[
                    'image_path' => $img
                ];
            }
            
            $img = str_replace('', '/', $img);
            if (!str_starts_with($img, '/')) {
                $img = '/' . ltrim($img, '/');
            }
            return (object)[
                'image_path' => $img
            ];
        }
        // 都沒有則返回預設圖片
        return (object)[
            'image_path' => '/images/placeholder.jpg'
        ];
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class);
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