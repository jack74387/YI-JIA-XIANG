<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-featured', function () {
    try {
        // 檢查是否有任何商品
        $allProducts = Product::count();
        echo "總共有 {$allProducts} 個商品<br>";
        
        // 檢查是否有 is_featured 欄位
        $hasColumn = Schema::hasColumn('products', 'is_featured');
        echo "products 表是否有 is_featured 欄位: " . ($hasColumn ? 'YES' : 'NO') . "<br>";
        
        // 嘗試查詢精選商品
        $featuredProducts = Product::where('is_featured', true)->get();
        echo "精選商品數量: " . $featuredProducts->count() . "<br>";
        
        // 嘗試查詢已發布的商品
        $publishedProducts = Product::where('status', 'published')->get();
        echo "已發布商品數量: " . $publishedProducts->count() . "<br>";
        
        // 顯示所有商品的狀態
        $allProductsWithStatus = Product::all(['id', 'name', 'status', 'is_featured']);
        echo "<h3>所有商品狀態:</h3>";
        foreach ($allProductsWithStatus as $product) {
            echo "ID: {$product->id}, 名稱: {$product->name}, 狀態: {$product->status}, 精選: " . ($product->is_featured ? 'YES' : 'NO') . "<br>";
        }
        
        return '';
    } catch (\Exception $e) {
        echo "錯誤: " . $e->getMessage() . "<br>";
        echo "文件: " . $e->getFile() . "<br>";
        echo "行數: " . $e->getLine() . "<br>";
        return '';
    }
});

// 這裡只放 web 相關路由，不要放 API 路由
// API 路由應該全部放在 routes/api.php 中
