<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;

class OrderController extends Controller
{
    // 建立訂單
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'shipping_method' => 'required|string|in:宅配,超商取貨,門市自取',
            'payment_method' => 'required|string|in:貨到付款,信用卡,LINE Pay',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 取得用戶購物車
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart || $cart->items()->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => '購物車是空的'
                ], 400);
            }

            // 計算總金額
            $total = 0;
            $cartItems = $cart->items()->with('product')->get();
            
            foreach ($cartItems as $item) {
                $price = $this->getPriceBySpec($item->product, $item->spec);
                $total += $price * $item->quantity;
            }

            // 建立訂單
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $total,
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_email' => $validated['recipient_email'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_method' => $validated['shipping_method'],
                'payment_method' => $validated['payment_method'],
                'note' => $validated['note'] ?? null,
            ]);

            // 建立訂單項目
            foreach ($cartItems as $item) {
                $price = $this->getPriceBySpec($item->product, $item->spec);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'spec' => $item->spec,
                ]);
            }

            // 清空購物車
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '訂單建立成功',
                'order_id' => $order->id,
                'order' => $this->formatOrder($order)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => '訂單建立失敗：' . $e->getMessage()
            ], 500);
        }
    }

    // 查看訂單詳情
    public function show($id)
    {
        $user = Auth::user();
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['items.product'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order' => $this->formatOrder($order)
        ]);
    }

    // 取得用戶訂單列表
    public function userOrders(Request $request)
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                return $this->formatOrder($order);
            });

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    // 更新訂單狀態
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => '訂單狀態更新成功',
            'order' => $this->formatOrder($order)
        ]);
    }

    // 後台訂單列表
    public function adminIndex(Request $request)
    {
        // 僅限管理員
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $query = Order::with(['user', 'items.product'])->orderBy('created_at', 'desc');
        // 搜尋條件：訂單編號、會員名稱
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('order_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%")
                         ;
                  });
            });
        }
        $perPage = $request->input('per_page', 12);
        $orders = $query->paginate($perPage);
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    // 根據規格取得價格
    private function getPriceBySpec($product, $spec)
    {
        if ($spec === 'large') {
            return $product->price_large;
        } elseif ($spec === 'small') {
            return $product->price_small;
        } elseif ($spec === 'sample') {
            $basePrice = $product->price_large ?: ($product->price_small * 2);
            return max(100, round($basePrice * 0.167));
        }
        return $product->price_small;
    }

    // 格式化訂單資料
    private function formatOrder($order)
    {
        return [
            'id' => $order->id,
            'status' => $order->status,
            'status_text' => $this->getStatusText($order->status),
            'total' => $order->total,
            'recipient_name' => $order->recipient_name,
            'recipient_phone' => $order->recipient_phone,
            'recipient_email' => $order->recipient_email,
            'shipping_address' => $order->shipping_address,
            'shipping_method' => $order->shipping_method,
            'payment_method' => $order->payment_method,
            'note' => $order->note,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'items' => $order->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'spec' => $item->spec,
                    'spec_text' => $this->getSpecText($item->spec),
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                    'image' => $item->product->primary_image?->image_path ?? $item->product->image ?? null,
                ];
            }),
        ];
    }

    // 取得狀態文字
    private function getStatusText($status)
    {
        $statusMap = [
            'pending' => '待處理',
            'processing' => '處理中',
            'shipped' => '已出貨',
            'delivered' => '已送達',
            'cancelled' => '已取消',
        ];
        return $statusMap[$status] ?? $status;
    }

    // 取得規格文字
    private function getSpecText($spec)
    {
        $specMap = [
            'large' => '600g',
            'small' => '300g',
            'sample' => '隨手包',
        ];
        return $specMap[$spec] ?? $spec;
    }
} 