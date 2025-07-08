<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // 假資料，實際應查詢資料庫
        $cart = [
            ['id' => 1, 'name' => '蜜汁原味豬肉乾', 'qty' => 2, 'price' => 340],
            ['id' => 2, 'name' => '杏仁厚片豬肉乾', 'qty' => 1, 'price' => 250],
        ];
        return response()->json(['success' => true, 'cart' => $cart]);
    }
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
        ]);
        // 實際應寫入資料庫
        return response()->json(['success' => true, 'message' => '已加入購物車', 'data' => $data]);
    }
    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
        ]);
        // 實際應更新資料庫
        return response()->json(['success' => true, 'message' => '購物車已更新', 'data' => $data]);
    }
    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
        ]);
        // 實際應刪除資料庫
        return response()->json(['success' => true, 'message' => '已移除商品', 'data' => $data]);
    }
} 