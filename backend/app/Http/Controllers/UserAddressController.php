<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;

class UserAddressController extends Controller
{
    // 取得會員所有常用地址
    public function index(Request $request)
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderByDesc('is_default')->orderByDesc('id')->get();
        return response()->json(['success' => true, 'data' => $addresses]);
    }

    // 新增常用地址
    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'recipient_email' => 'nullable|email|max:100',
            'city' => 'required|string|max:20',
            'district' => 'required|string|max:20',
            'detail_address' => 'required|string|max:200',
            'is_default' => 'boolean',
        ]);
        if (!empty($validated['is_default'])) {
            // 取消其他預設
            $user->addresses()->update(['is_default' => false]);
        }
        $address = $user->addresses()->create($validated);
        return response()->json(['success' => true, 'data' => $address]);
    }

    // 更新常用地址
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'recipient_email' => 'nullable|email|max:100',
            'city' => 'required|string|max:20',
            'district' => 'required|string|max:20',
            'detail_address' => 'required|string|max:200',
            'is_default' => 'boolean',
        ]);
        if (!empty($validated['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }
        $address->update($validated);
        return response()->json(['success' => true, 'data' => $address]);
    }

    // 刪除常用地址
    public function destroy($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $address->delete();
        return response()->json(['success' => true]);
    }

    // 設為預設地址
    public function setDefault($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return response()->json(['success' => true, 'data' => $address]);
    }
} 