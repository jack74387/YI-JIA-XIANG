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
            $featuredProducts = Product::with('category')
                ->where('is_featured', true)
                ->where('status', 'published')
                ->orderBy('featured_order', 'asc')
                ->orderBy('id', 'asc')
                ->limit(8) // 限制最多8個精選商品
                ->get();

            return response()->json([
                'success' => true,
                'data' => $featuredProducts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '獲取精選商品失敗：' . $e->getMessage()
            ], 500);
        }
    }
} 