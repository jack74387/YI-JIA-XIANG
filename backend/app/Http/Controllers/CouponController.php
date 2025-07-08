<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = [
            ['code' => 'WELCOME95', 'description' => '新客首購95折'],
            ['code' => 'FREESHIP', 'description' => '滿千免運'],
        ];
        return response()->json(['success' => true, 'coupons' => $coupons]);
    }
    public function redeem(Request $request)
    {
        $data = $request->validate(['code' => 'required|string']);
        // 實際應驗證與兌換
        return response()->json(['success' => true, 'message' => '優惠券已兌換', 'code' => $data['code']]);
    }
} 