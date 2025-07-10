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
        $items = $cart->items()->with('product')->get();
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
        ]);
        $item = $cart->items()->where('product_id', $validated['product_id'])
            ->when(isset($validated['spec']), function($q) use ($validated) {
                $q->where('spec', $validated['spec']);
            })->first();
        if ($item) {
            $item->quantity += $validated['quantity'];
            $item->save();
        } else {
            $item = $cart->items()->create($validated);
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
        $price = $product->price_small;
        if ($spec === 'large') $price = $product->price_large;
        elseif ($spec === 'small') $price = $product->price_small;
        elseif ($spec === 'sample') {
            $basePrice = $product->price_large ?: ($product->price_small * 2);
            $price = max(100, round($basePrice * 0.167));
        }
        return [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'name' => $product->name,
            'image' => $product->primary_image?->image_path ?? $product->image ?? null,
            'spec' => $spec,
            'quantity' => $item->quantity,
            'price' => $price,
            'product' => $product,
        ];
    }
} 