<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Mail\OrderStatusNotification;

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
            'shipping_address' => 'nullable|string',
            'city' => 'nullable|string',
            'district' => 'nullable|string',
            'detail_address' => 'nullable|string',
            'shipping_method' => 'required|string|in:宅配,超商取貨,門市自取',
            'payment_method' => 'required|string|in:貨到付款,信用卡,LINE Pay',
            'note' => 'nullable|string',
            'final_amount' => 'required|integer|min:0',
            'discount' => 'required|integer|min:0',
            'point_discount' => 'nullable|integer|min:0',
            'used_points' => 'nullable|integer|min:0',
            // 門市自取相關欄位
            'store_id' => 'nullable|string',
            'store_name' => 'nullable|string',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string',
            'store_hours' => 'nullable|string',
            // 超商取貨相關欄位
            'convenience_store_name' => 'nullable|string',
            'convenience_store_address' => 'nullable|string',
            'convenience_store_phone' => 'nullable|string',
            'convenience_store_chain' => 'nullable|string',
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

            // 點數折抵與扣除
            $usedPoints = $request->input('used_points', 0);
            $pointDiscount = $request->input('point_discount', 0);
            if ($usedPoints > 0) {
                if ($user->points < $usedPoints) {
                    return response()->json(['success' => false, 'message' => '點數不足'], 400);
                }
                // 扣除點數
                $user->points -= $usedPoints;
                $user->save();
            }

            // 計算總金額
            $total = 0;
            $cartItems = $cart->items()->with('product')->get();
            
            // 檢查購物車中是否有無法購買的商品
            foreach ($cartItems as $item) {
                if (!$item->product || !$item->product->canAddToCart()) {
                    return response()->json([
                        'success' => false,
                        'message' => '購物車中有商品目前無法購買，請重新整理購物車'
                    ], 400);
                }
            }
            
            foreach ($cartItems as $item) {
                $price = $item->price ?? $this->getPriceBySpec($item->product, $item->spec);
                $total += $price * $item->quantity;
            }

            // 建立訂單
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $total,
                'final_amount' => $validated['final_amount'],
                'discount' => $validated['discount'],
                'point_discount' => $pointDiscount,
                'used_points' => $usedPoints,
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_email' => $validated['recipient_email'],
                'shipping_address' => $validated['city'].$validated['district'].$validated['detail_address'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'detail_address' => $validated['detail_address'],
                'shipping_method' => $validated['shipping_method'],
                'payment_method' => $validated['payment_method'],
                'note' => $validated['note'] ?? null,
                // 門市自取相關欄位
                'store_id' => $validated['store_id'],
                'store_name' => $validated['store_name'],
                'store_address' => $validated['store_address'],
                'store_phone' => $validated['store_phone'],
                'store_hours' => $validated['store_hours'],
                // 超商取貨相關欄位
                'convenience_store_name' => $validated['convenience_store_name'],
                'convenience_store_address' => $validated['convenience_store_address'],
                'convenience_store_phone' => $validated['convenience_store_phone'],
                'convenience_store_chain' => $validated['convenience_store_chain'],
            ]);

            // 點數異動紀錄（要在訂單建立後補上 order_id）
            if ($usedPoints > 0) {
                \App\Models\PointTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'spend',
                    'points' => -$usedPoints,
                    'description' => '訂單折抵（訂單建立時自動扣除）',
                    'order_id' => $order->id
                ]);
            }

            // 優惠券標記為已使用
            if ($request->has('coupon_id') && $request->coupon_id) {
                $couponId = $request->coupon_id;
                $userCoupon = \App\Models\UserCoupon::where('user_id', $user->id)
                    ->where('coupon_id', $couponId)
                    ->first();
                if ($userCoupon) {
                    $userCoupon->is_used = true;
                    $userCoupon->used_at = now();
                    $userCoupon->order_id = $order->id;
                    $userCoupon->save();
                } else {
                    // 若無領取紀錄則自動建立一筆
                    \App\Models\UserCoupon::create([
                        'user_id' => $user->id,
                        'coupon_id' => $couponId,
                        'is_used' => true,
                        'used_at' => now(),
                        'order_id' => $order->id
                    ]);
                }
            }

            // 建立訂單項目 & 扣庫存
            foreach ($cartItems as $item) {
                // 若購物車有自訂 price/weight 則優先用
                $price = $item->price ?? $this->getPriceBySpec($item->product, $item->spec);
                $weight = $item->weight ?? null;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'spec' => $item->spec,
                    'weight' => $weight,
                ]);

                // 扣庫存 - 優先使用 spec name 匹配，不管 spec_id 有沒有傳遞
                if ($item->spec) {
                    // 先嘗試用 spec name 找規格
                    $spec = \App\Models\ProductSpec::where('product_id', $item->product_id)
                        ->where('name', $item->spec)
                        ->first();
                    
                    if ($spec) {
                        // 找到規格，扣規格庫存
                        Log::info('扣庫存-規格-name', [
                           'product_id' => $item->product_id,
                           'spec' => $item->spec,                  'spec_found' => $spec->id,
                           'before' => $spec->stock,
                          'qty' => $item->quantity
                        ]);
                        $spec->stock = max(0, $spec->stock - $item->quantity);
                        $spec->save();
                        Log::info('扣庫存-規格-name-完成', ['after' => $spec->stock]);
                        
                        // 自動切換商品狀態
                        $product = $spec->product;
                        if ($spec->stock <= 5) {
                            $product->status = 'notification';
                            $product->save();
                        } elseif ($spec->stock > 5 && $product->status === 'notification') {
                            // 檢查該商品所有規格是否都 > 5
                            $allSpecsAbove5 = $product->specs()->where('stock', '<=', 5)->count() === 0;
                            if ($allSpecsAbove5) {
                                $product->status = 'published';
                                $product->save();
                            }
                        }
                    } else {
                        // 沒找到規格，扣商品本身庫存
                        $product = \App\Models\Product::find($item->product_id);
                        Log::info('扣庫存-商品（規格不存在）', [
                           'product_id' => $item->product_id,
                           'spec' => $item->spec,              'before' => $product ? $product->stock : null,
                          'qty' => $item->quantity
                        ]);
                        if ($product) {
                            $product->stock = max(0, $product->stock - $item->quantity);
                            
                            // 自動切換商品狀態
                            if ($product->stock <= 5) {
                                $product->status = 'notification';
                            } elseif ($product->stock > 5 && $product->status === 'notification') {
                                $product->status = 'published';
                            }
                            
                            $product->save();
                            Log::info('扣庫存-商品-完成', ['after' => $product->stock]);
                        }
                    }
                } else {
                    // 沒有 spec，直接扣商品庫存
                    $product = \App\Models\Product::find($item->product_id);
                    Log::info('扣庫存-商品（無規格）', [
                       'product_id' => $item->product_id,
                   'before' => $product ? $product->stock : null,
                      'qty' => $item->quantity
                    ]);
                    if ($product) {
                        $product->stock = max(0, $product->stock - $item->quantity);
                        
                        // 自動切換商品狀態
                        if ($product->stock <= 5) {
                            $product->status = 'notification';
                        } elseif ($product->stock > 5 && $product->status === 'notification') {
                            $product->status = 'published';
                        }
                        
                        $product->save();
                        Log::info('扣庫存-商品-完成', ['after' => $product->stock]);
                    }
                }
            }

            // 清空購物車
            $cart->items()->delete();

            // 計算並累積點數（滿百元消費累積一點）
            $this->calculateAndAddPoints($order, $user);

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

        // 狀態變更為 delivered 時，檢查會員等級
        if ($validated['status'] === 'delivered' && $order->user) {
            $order->user->checkLevelUpgrade();
        }

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
        
        // 調試資訊
        if (!$user) {
            return response()->json(['success' => false, 'message' => '未認證'], 401);
        }
        
        if (!$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無管理員權限'], 403);
        }
        $query = Order::with(['user', 'items.product.primary_image'])->orderBy('created_at', 'desc');
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
        // 狀態篩選
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        $perPage = $request->input('per_page', 12);
        $orders = $query->paginate($perPage);
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    // 管理員查詢訂單詳情
    public function adminShow($id)
    {
        $user = Auth::user();
        // if (!$user || !$user->is_admin) {
        //     return response()->json(['success' => false, 'message' => '無權限'], 403);
        // }
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'order' => $this->formatOrder($order)
        ]);
    }

    // 管理員更新訂單狀態
    public function adminUpdateStatus(Request $request, $id)
    {
        $user = Auth::user();
        // if (!$user || !$user->is_admin) {
        //     return response()->json(['success' => false, 'message' => '無權限'], 403);
        // }
        
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        $oldStatus = $order->status;
        
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        $newStatus = $validated['status'];
        
        // 更新訂單狀態
        $order->update(['status' => $newStatus]);
        
        // 狀態變更為 delivered 時，檢查會員等級
        if ($newStatus === 'delivered' && $order->user) {
            $order->user->checkLevelUpgrade();
        }
        
        // 發送郵件通知（除了待處理狀態，其他狀態變更都發送郵件）
        if ($newStatus !== 'pending' && $order->user && $order->user->email) {
            try {
                // 格式化訂單數據以確保郵件中的數據與管理頁面一致
                $formattedOrder = $this->formatOrderForEmail($order);
                Mail::to($order->user->email)->send(new OrderStatusNotification($formattedOrder, $oldStatus, $newStatus));
                Log::info("訂單狀態變更郵件已發送", [
                    'order_id' => $order->id,
                    'user_email' => $order->user->email,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            } catch (\Exception $e) {
                Log::error("訂單狀態變更郵件發送失敗", [
                    'order_id' => $order->id,
                    'user_email' => $order->user->email,
                    'error' => $e->getMessage()
                ]);
                // 郵件發送失敗不影響狀態更新，只記錄錯誤
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => '訂單狀態更新成功',
            'order' => $this->formatOrder($order)
        ]);
    }

    // 匯出訂單
    public function exportOrders(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        $query = Order::with(['user', 'items.product'])->orderBy('created_at', 'desc');
        
        // 搜尋條件
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('order_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }
        
        // 狀態篩選
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        // 生成 CSV 內容
        $csvContent = '';
        
        // 添加 BOM 以支援中文
        $csvContent .= chr(0xEF).chr(0xBB).chr(0xBF);
        
        // CSV 標題
        $csvContent .= "訂單編號,會員姓名,會員Email,收件人姓名,收件人電話,收件地址,配送方式,付款方式,訂單狀態,訂單金額,建立時間,商品明細\n";

        foreach ($orders as $order) {
            $items = $order->items->map(function($item) {
                return $item->product->name . ' (' . $this->getSpecText($item->spec) . ') x' . $item->quantity;
            })->implode('; ');

            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $order->id,
                $order->user->name ?? '',
                $order->user->email ?? '',
                $order->recipient_name,
                $order->recipient_phone,
                $order->shipping_address,
                $order->shipping_method,
                $order->payment_method,
                $this->getStatusText($order->status),
                $order->total,
                $order->created_at->format('Y-m-d H:i:s'),
                $items
            );
        }

        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
            'final_amount' => $order->final_amount,
            'discount' => $order->discount,
            'point_discount' => $order->point_discount,
            'used_points' => $order->used_points,
            'recipient_name' => $order->recipient_name,
            'recipient_phone' => $order->recipient_phone,
            'recipient_email' => $order->recipient_email,
            'shipping_address' => $order->shipping_address,
            'city' => $order->city,
            'district' => $order->district,
            'detail_address' => $order->detail_address,
            'shipping_method' => $order->shipping_method,
            'payment_method' => $order->payment_method,
            'note' => $order->note,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            // 門市自取相關資訊
            'store_id' => $order->store_id,
            'store_name' => $order->store_name,
            'store_address' => $order->store_address,
            'store_phone' => $order->store_phone,
            'store_hours' => $order->store_hours,
            // 超商取貨相關資訊
            'convenience_store_name' => $order->convenience_store_name,
            'convenience_store_address' => $order->convenience_store_address,
            'convenience_store_phone' => $order->convenience_store_phone,
            'convenience_store_chain' => $order->convenience_store_chain,
            'user' => $order->user ? [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ] : null,
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
                    'weight' => $item->weight,
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

    // 為郵件格式化訂單數據
    private function formatOrderForEmail($order)
    {
        // 創建一個新的 order 對象，包含格式化的 items 數據
        $formattedOrder = clone $order;
        
        // 將 items 轉換為具有正確數據的集合
        $formattedOrder->items = $order->items->map(function($item) {
            // 創建一個包含正確數據的對象
            $formattedItem = (object) [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name, // 使用商品表中的最新名稱
                'spec' => $item->spec,
                'spec_text' => $this->getSpecText($item->spec), // 格式化規格文字
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->price * $item->quantity, // 計算正確的小計
                'weight' => $item->weight,
            ];
            
            return $formattedItem;
        });
        
        return $formattedOrder;
    }

    private function getSpecText($spec)
    {
        $specMap = [
            'large' => '600g',
            'small' => '300g',
            'sample' => '隨手包',
        ];
        return $specMap[$spec] ?? $spec;
    }

    /**
     * 計算並累積點數
     * 規則：滿百元消費累積一點
     */
    private function calculateAndAddPoints($order, $user)
    {
        try {
            // 計算點數（滿百元消費累積一點）
            $points = intval($order->total / 100);
            
            if ($points > 0) {
                // 檢查是否為生日當月（雙倍點數）
                $isBirthdayMonth = $this->isBirthdayMonth($user);
                if ($isBirthdayMonth) {
                    $points *= 2;
                }

                // 只呼叫 addPoints，不再重複建立 PointTransaction
                $user->addPoints($points, "購物消費獲得點數（訂單 #{$order->id}）" . ($isBirthdayMonth ? ' - 生日當月雙倍' : ''));

                \Log::info("用戶 {$user->id} 購物消費 {$order->total} 元，獲得 {$points} 點", [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'order_total' => $order->total,
                    'points_earned' => $points,
                    'is_birthday_month' => $isBirthdayMonth
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("累積點數失敗：{$e->getMessage()}", [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'order_total' => $order->total
            ]);
        }
    }

    /**
     * 檢查是否為生日當月
     */
    private function isBirthdayMonth($user)
    {
        if (!$user->birthday) {
            return false;
        }

        $birthday = \Carbon\Carbon::parse($user->birthday);
        $now = \Carbon\Carbon::now();

        return $birthday->month === $now->month;
    }
} 