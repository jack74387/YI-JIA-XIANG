<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

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
        $data = $product->toArray();
        $data['category_name'] = $product->category ? $product->category->name : null;
        return response()->json([
            'success' => true,
            'product' => $data
        ]);
    }
} 