<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    // 取得所有上架商品及庫存（支援搜尋、分頁）
    public function adminInventories(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $query = Product::with(['category', 'specs.prices'])
                ->whereIn('status', ['published', 'notification']); // 顯示上架與通知狀態的商品

            // 搜尋
            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            // 分類篩選
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            $products = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 15));
            $inventories = [];
            foreach ($products as $product) {
                $specs = $product->specs ?? [];
                
                if (empty($specs) || count($specs) === 0) {
                    // 沒有規格的商品
                    $isLow = ($product->stock ?? 0) < ($product->alert_level ?? 10);
                    
                    // 檢查警戒值篩選條件
                    $shouldInclude = true;
                    if ($request->has('alert_level') && $request->alert_level) {
                        if ($request->alert_level === 'low' && !$isLow) {
                            $shouldInclude = false;
                        } elseif ($request->alert_level === 'normal' && $isLow) {
                            $shouldInclude = false;
                        }
                    }
                    
                    if ($shouldInclude) {
                        $inventories[] = [
                            'id' => $product->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'category' => $product->category,
                            'spec_name' => '基本規格',
                            'stock' => $product->stock ?? 0,
                            'alert_level' => $product->alert_level ?? 10,
                            'prices' => [
                                ['price' => $product->price_large, 'label' => '大包裝'],
                                ['price' => $product->price_small, 'label' => '小包裝'],
                            ],
                            'has_specs' => false
                        ];
                    }
                } else {
                    // 有規格的商品
                    foreach ($specs as $spec) {
                        $isLow = ($spec->stock ?? 0) < ($spec->alert_level ?? 10);
                        
                        // 檢查警戒值篩選條件
                        $shouldInclude = true;
                        if ($request->has('alert_level') && $request->alert_level) {
                            if ($request->alert_level === 'low' && !$isLow) {
                                $shouldInclude = false;
                            } elseif ($request->alert_level === 'normal' && $isLow) {
                                $shouldInclude = false;
                            }
                        }
                        
                        if ($shouldInclude) {
                            $inventories[] = [
                                'id' => $spec->id,
                                'product_id' => $product->id,
                                'product_name' => $product->name,
                                'category' => $product->category,
                                'spec_name' => $spec->name,
                                'stock' => $spec->stock ?? 0,
                                'alert_level' => $spec->alert_level ?? 10,
                                'prices' => $spec->prices ? $spec->prices->map(function($p) {
                                    return ['price' => $p->price, 'label' => $p->label];
                                })->toArray() : [],
                                'has_specs' => true
                            ];
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $inventories,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '獲取庫存資料失敗：' . $e->getMessage()
            ], 500);
        }
    }

    // 調整庫存
    public function adminInventoryAdjust(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }

        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:0',
                'alert_level' => 'nullable|integer|min:0'
            ]);

            // 檢查是商品規格還是商品本身
            $spec = \App\Models\ProductSpec::find($id);
            if ($spec) {
                // 調整規格庫存
                $spec->stock = $validated['quantity'];
                if (isset($validated['alert_level'])) {
                    $spec->alert_level = $validated['alert_level'];
                }
                $spec->save();
                
                // 自動切換商品狀態
                $product = $spec->product;
                if ($spec->stock <= 5) {
                    $product->status = 'notification';
                    $product->save();
                } elseif ($product->status === 'notification') { // 檢查該商品所有規格是否都 > 5
                    $allSpecsAbove5 = $product->specs()->where('stock', '<=', 5)->count() === 0;
                    if ($allSpecsAbove5) {
                        $product->status = 'published';
                        $product->save();
                    }
                }
                
                return response()->json(['success' => true, 'message' => '更新']);
            } else {
                // 調整商品本身庫存
                $product = Product::find($id);
                if ($product) {
                    $product->stock = $validated['quantity'];
                    if (isset($validated['alert_level'])) {
                        $product->alert_level = $validated['alert_level'];
                    }
                    
                    // 自動切換商品狀態
                    if ($product->stock <= 5) {
                        $product->status = 'notification';
                    } elseif ($product->status === 'notification') {
                        $product->status = 'published';
                    }
                    
                    $product->save();
                    return response()->json(['success' => true, 'message' => '更新']);
                }
            }

            return response()->json(['success' => false, 'message' => '找不到對應的商品或規格']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '更新庫存失敗：' . $e->getMessage()
            ], 500);
        }
    }
} 