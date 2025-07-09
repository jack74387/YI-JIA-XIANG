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
        return response()->json([
            'success' => true,
            'data' => $items
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
        ]);
        $item = $cart->items()->where('product_id', $validated['product_id'])->first();
        if ($item) {
            $item->quantity += $validated['quantity'];
            $item->save();
        } else {
            $item = $cart->items()->create($validated);
        }
        return response()->json(['success' => true, 'item' => $item->load('product')]);
    }

    // 更新購物車數量
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $item = $cart->items()->where('id', $id)->firstOrFail();
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $item->quantity = $validated['quantity'];
        $item->save();
        return response()->json(['success' => true, 'item' => $item->load('product')]);
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
} 