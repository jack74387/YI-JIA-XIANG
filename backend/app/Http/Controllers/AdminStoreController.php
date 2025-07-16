<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;

class AdminStoreController extends Controller
{
    // 取得門市列表
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無管理員權限'], 403);
        }

        $stores = Store::orderBy('sort_order', 'asc')->get();
        return response()->json(['success' => true, 'stores' => $stores]);
    }

    // 取得單一門市
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無管理員權限'], 403);
        }

        $store = Store::findOrFail($id);
        return response()->json(['success' => true, 'store' => $store]);
    }

    // 建立門市
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無管理員權限'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'hours' => 'nullable|string|max:100',
            'map' => 'nullable|url',
            'map_link' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $store = Store::create($validated);
        return response()->json(['success' => true, 'store' => $store, 'message' => '門市建立成功']);
    }

    // 更新門市
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無管理員權限'], 403);
        }

        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'hours' => 'nullable|string|max:100',
            'map' => 'nullable|url',
            'map_link' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $store->update($validated);
        return response()->json(['success' => true, 'store' => $store, 'message' => '門市更新成功']);
    }

    // 刪除門市
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無管理員權限'], 403);
        }

        $store = Store::findOrFail($id);
        $store->delete();
        return response()->json(['success' => true, 'message' => '門市刪除成功']);
    }
}
