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
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        
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
} 