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
        return response()->json([
            'success' => true,
            'message' => 'API endpoint is working',
            'data' => []
        ]);
    }
} 