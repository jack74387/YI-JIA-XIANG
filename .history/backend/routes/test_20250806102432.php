<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::get('/test-featured', function () {
    try {
        // 檢查是否有任何商品
        $allProducts = Product::count();
        echo "總共有 {$allProducts} 個商品\n";
        
        // 檢查是否有 is_featured 欄位
        $hasColumn = Schema::hasColumn('products', 'is_featured');
        echo "products 表是否有 is_featured 欄位: " . ($hasColumn ? 'YES' : 'NO') . "\n";
        
        // 嘗試查詢精選商品
        $featuredProducts = Product::where('is_featured', true)->get();
        echo "精選商品數量: " . $featuredProducts->count() . "\n";
        
        // 嘗試查詢已發布的商品
        $publishedProducts = Product::where('status', 'published')->get();
        echo "已發布商品數量: " . $publishedProducts->count() . "\n";
        
        return [
            'total_products' => $allProducts,
            'has_featured_column' => $hasColumn,
            'featured_products_count' => $featuredProducts->count(),
            'published_products_count' => $publishedProducts->count()
        ];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});
