<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    // 取得購物車
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $items = $cart->items()->with(['product.primary_image'])->get();
        $data = $items->map(function($item) { return $this->formatCartItem($item); });
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // 加入購物車
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'spec' => 'nullable|string',
            'spec_id' => 'nullable|integer', // 新增
            'price' => 'nullable|integer|min:1', // 新增
            'weight' => 'nullable|string', // 新增
        ]);

        // 檢查商品是否可以加入購物車
        $product = Product::find($validated['product_id']);
        if (!$product || !$product->canAddToCart()) {
            return response()->json([
                'success' => false,
                'message' => '此商品目前無法加入購物車'
            ], 400);
        }

        $item = $cart->items()->where('product_id', $validated['product_id'])
            ->when(isset($validated['spec']), function($q) use ($validated) {
                $q->where('spec', $validated['spec']);
            })
            ->when(isset($validated['spec_id']), function($q) use ($validated) {
                $q->where('spec_id', $validated['spec_id']);
            })
            ->when(isset($validated['weight']), function($q) use ($validated) {
                $q->where('weight', $validated['weight']);
            })
            ->first();
        if ($item) {
            $item->quantity += $validated['quantity'];
            // 若 sample 且有 price，更新 price
            if (($validated['spec'] ?? null) === 'sample' && isset($validated['price'])) {
                $item->price = $validated['price'];
            }
            // 若有 weight，更新 weight
            if (isset($validated['weight'])) {
                $item->weight = $validated['weight'];
            }
            // 若有 spec_id，更新 spec_id
            if (isset($validated['spec_id'])) {
                $item->spec_id = $validated['spec_id'];
            }
            $item->save();
        } else {
            $data = $validated;
            // 若 sample 且有 price，存入 price
            if (($validated['spec'] ?? null) === 'sample' && isset($validated['price'])) {
                $data['price'] = $validated['price'];
            }
            // 若有 weight，存入 weight
            if (isset($validated['weight'])) {
                $data['weight'] = $validated['weight'];
            }
            // 若有 spec_id，存入 spec_id
            if (isset($validated['spec_id'])) {
                $data['spec_id'] = $validated['spec_id'];
            }
            $item = $cart->items()->create($data);
        }
        return response()->json(['success' => true, 'item' => $this->formatCartItem($item)]);
    }

    // 更新購物車數量與規格
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $item = $cart->items()->where('id', $id)->firstOrFail();
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'spec' => 'nullable|string',
        ]);
        $item->quantity = $validated['quantity'];
        if (isset($validated['spec'])) {
            $item->spec = $validated['spec'];
        }
        $item->save();
        return response()->json(['success' => true, 'item' => $this->formatCartItem($item)]);
    }

    // 刪除購物車項目
    public function destroy($id)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $item = $cart->items()->where('id', $id)->firstOrFail();
        $item->delete();
        return response()->json(['success' => true]);
    }

    // 格式化購物車商品，帶上 spec 與對應價格
    private function formatCartItem($item)
    {
        $product = $item->product;
        $spec = $item->spec;
        // 預設價格
        $price = $product->price_small;
        if ($spec === 'large') $price = $product->price_large;
        elseif ($spec === 'small') $price = $product->price_small;
        elseif ($spec === 'sample') {
            // 僅用 item->price，不 fallback
            $price = $item->price;
        }
        return [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'name' => $product->name,
            'image' => $product->primary_image?->image_path ?? $product->image ?? null,
            'spec' => $spec,
            'spec_id' => $item->spec_id ?? null, // 新增
            'weight' => $item->weight ?? null, // 新增 weight 回傳
            'quantity' => $item->quantity,
            'price' => $price,
            'product' => $product,
        ];
    }
} 