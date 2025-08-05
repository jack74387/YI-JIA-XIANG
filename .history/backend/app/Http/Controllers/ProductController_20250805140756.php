<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // 只顯示上架和通知狀態的產品
        $query->whereIn('status', ['published', 'notification']);

        // 搜尋
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%");
        }

        // 分類過濾
        if ($category = $request->input('category_id')) {
            $query->where('category_id', $category);
        }

        // 排序
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // 分頁
        $perPage = $request->input('per_page', 12);
        $products = $query->paginate($perPage);
        
        // 為每個商品添加 can_add_to_cart 和 primary_image 欄位
        $products->getCollection()->transform(function($product) {
            $product->can_add_to_cart = $product->canAddToCart();
            $product->primary_image = $product->primary_image;
            return $product;
        });
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'specs'])->findOrFail($id);
        
        // 檢查產品是否可見
        if (!$product->isVisible()) {
            return response()->json([
                'success' => false,
                'message' => '產品不存在或已下架'
            ], 404);
        }
        
        $data = $product->toArray();
        $data['category_name'] = $product->category ? $product->category->name : null;
        $data['can_add_to_cart'] = $product->canAddToCart();
        
        return response()->json([
            'success' => true,
            'product' => $data
        ]);
    }

    /**
     * 取得商品推薦
     * Phase 1: 基礎推薦系統
     * - 同分類商品推薦
     * - 相似價格區間推薦
     * - 熱門商品推薦（備用）
     */
    public function getRecommendations($id, Request $request)
    {
        $product = Product::with('category')->findOrFail($id);
        
        // 檢查產品是否可見
        if (!$product->isVisible()) {
            return response()->json([
                'success' => false,
                'message' => '產品不存在或已下架'
            ], 404);
        }

        $limit = $request->input('limit', 8); // 預設回傳8個推薦商品
        $recommendations = collect();

        // 策略1: 同分類商品推薦（排除自己）
        if ($product->category_id) {
            $categoryProducts = Product::with('category')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $id)
                ->where('status', 'published') // 只推薦上架商品
                ->orderBy('sold_count', 'desc') // 優先推薦銷量高的
                ->orderBy('rating', 'desc')
                ->take($limit)
                ->get();
            
            $recommendations = $recommendations->merge($categoryProducts);
        }

        // 策略2: 相似價格區間推薦（如果同分類商品不足）
        // 使用 price_large 作為主要價格參考，如果沒有則使用 price_small
        $productPrice = $product->price_large ?? $product->price_small ?? 0;
        
        if ($recommendations->count() < $limit && $productPrice > 0) {
            $priceRange = $productPrice * 0.3; // 價格範圍±30%
            $minPrice = $productPrice - $priceRange;
            $maxPrice = $productPrice + $priceRange;
            
            $similarPriceProducts = Product::with('category')
                ->where('id', '!=', $id)
                ->where(function($query) use ($minPrice, $maxPrice) {
                    $query->where(function($q) use ($minPrice, $maxPrice) {
                        // 使用 price_large 進行比較
                        $q->whereBetween('price_large', [$minPrice, $maxPrice]);
                    })->orWhere(function($q) use ($minPrice, $maxPrice) {
                        // 如果 price_large 為空，使用 price_small
                        $q->whereNull('price_large')
                          ->whereBetween('price_small', [$minPrice, $maxPrice]);
                    });
                })
                ->where('status', 'published') // 只推薦上架商品
                ->whereNotIn('id', $recommendations->pluck('id')->toArray()) // 排除已推薦的
                ->orderBy('rating', 'desc')
                ->orderBy('sold_count', 'desc')
                ->take($limit - $recommendations->count())
                ->get();
            
            $recommendations = $recommendations->merge($similarPriceProducts);
        }

        // 策略3: 熱門商品推薦（備用方案）
        if ($recommendations->count() < $limit) {
            $popularProducts = Product::with('category')
                ->where('id', '!=', $id)
                ->where('status', 'published') // 只推薦上架商品
                ->whereNotIn('id', $recommendations->pluck('id')->toArray()) // 排除已推薦的
                ->where(function($query) {
                    $query->where('hot', true)
                          ->orWhere('sold_count', '>', 0)
                          ->orWhere('rating', '>=', 4);
                })
                ->orderBy('hot', 'desc')
                ->orderBy('sold_count', 'desc')
                ->orderBy('rating', 'desc')
                ->take($limit - $recommendations->count())
                ->get();
            
            $recommendations = $recommendations->merge($popularProducts);
        }

        // 如果還是不足，則推薦最新商品
        if ($recommendations->count() < $limit) {
            $latestProducts = Product::with('category')
                ->where('id', '!=', $id)
                ->whereIn('status', ['published', 'notification'])
                ->whereNotIn('id', $recommendations->pluck('id')->toArray()) // 排除已推薦的
                ->orderBy('created_at', 'desc')
                ->take($limit - $recommendations->count())
                ->get();
            
            $recommendations = $recommendations->merge($latestProducts);
        }

        // 限制結果數量並添加必要欄位
        $recommendations = $recommendations->take($limit)->map(function($product) {
            $product->can_add_to_cart = $product->canAddToCart();
            $product->primary_image = $product->primary_image;
            $product->category_name = $product->category ? $product->category->name : null;
            
            // 計算顯示價格
            $product->display_price = $product->price_large ?? $product->price_small ?? 0;
            
            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $id,
                'product_name' => $product->name,
                'product_price' => $productPrice,
                'recommendations' => $recommendations->values()->all(),
                'recommendation_count' => $recommendations->count(),
                'strategies_used' => [
                    'category_based' => $product->category_id ? true : false,
                    'price_based' => $productPrice > 0 ? true : false,
                    'popular_fallback' => true
                ]
            ]
        ]);
    }
} 