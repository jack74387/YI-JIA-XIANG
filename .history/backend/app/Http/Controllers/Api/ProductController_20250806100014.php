<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 取得商品列表
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // 取得單一商品
    public function show(Product $product)
    {
        return response()->json($product);
    }

    // 取得精選商品
    public function getFeaturedProducts()
    {
        try {
            // 簡化版本先測試基本查詢
            $featuredProducts = Product::where('is_featured', true)
                ->where('status', 'published')
                ->orderBy('id', 'asc')
                ->limit(8)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $featuredProducts,
                'count' => $featuredProducts->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('獲取精選商品失敗', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '獲取精選商品失敗：' . $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
} 