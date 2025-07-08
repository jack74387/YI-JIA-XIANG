<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'cart' => 'required|array',
            'address' => 'required|string',
            'payment' => 'required|string',
        ]);
        // 實際應建立訂單
        return response()->json(['success' => true, 'order_id' => 1001, 'data' => $data]);
    }
    public function show($id)
    {
        // 假資料
        $order = [
            'id' => $id,
            'status' => '已完成',
            'amount' => 680,
            'items' => [
                ['id' => 1, 'name' => '蜜汁原味豬肉乾', 'qty' => 2, 'price' => 340],
                ['id' => 2, 'name' => '杏仁厚片豬肉乾', 'qty' => 1, 'price' => 250],
            ],
        ];
        return response()->json(['success' => true, 'order' => $order]);
    }
    public function userOrders(Request $request)
    {
        // 假資料
        $orders = [
            ['id' => 1001, 'amount' => 680, 'status' => '已完成'],
            ['id' => 1002, 'amount' => 340, 'status' => '處理中'],
        ];
        return response()->json(['success' => true, 'orders' => $orders]);
    }
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate(['status' => 'required|string']);
        // 實際應更新訂單狀態
        return response()->json(['success' => true, 'order_id' => $id, 'status' => $data['status']]);
    }
} 