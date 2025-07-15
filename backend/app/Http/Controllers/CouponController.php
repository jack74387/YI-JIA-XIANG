<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\OperationLog;

class CouponController extends Controller
{
    /**
     * 取得優惠券列表（前台用）
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Coupon::where('is_active', true);
        
        // 搜尋功能
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // 如果用戶已登入，添加用戶使用狀態
        if ($user) {
            $coupons->getCollection()->transform(function($coupon) use ($user) {
                $userCoupon = $coupon->userCoupons()->where('user_id', $user->id)->first();
                $coupon->user_status = $userCoupon ? ($userCoupon->is_used ? 'used' : 'claimed') : 'available';
                return $coupon;
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => $coupons
        ]);
    }

    /**
     * 兌換優惠券（前台用）
     */
    public function redeem(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon = Coupon::where('code', $request->code)->first();
            
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => '優惠券不存在'
                ], 404);
            }

            if (!$coupon->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => '優惠券已失效'
                ], 400);
            }

            // 檢查用戶是否已經領取過
            $existingUserCoupon = UserCoupon::where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->first();

            if ($existingUserCoupon) {
                return response()->json([
                    'success' => false,
                    'message' => '您已經領取過此優惠券'
                ], 400);
            }

            // 創建用戶優惠券記錄
            UserCoupon::create([
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'is_used' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => '優惠券領取成功',
                'coupon' => $coupon
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '領取失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 驗證優惠券（結帳時使用）
     */
    public function validate(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'order_amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon = Coupon::where('code', $request->code)->first();
            
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => '優惠券不存在'
                ], 404);
            }

            if (!$coupon->canBeUsedByUser($user, $request->order_amount)) {
                return response()->json([
                    'success' => false,
                    'message' => '優惠券無法使用'
                ], 400);
            }

            $discount = $coupon->calculateDiscount($request->order_amount);

            return response()->json([
                'success' => true,
                'coupon' => $coupon,
                'discount' => $discount,
                'discount_text' => $coupon->discount_text
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 取得用戶的優惠券（前台用）
     */
    public function userCoupons(Request $request)
    {
        $user = Auth::user();
        
        $userCoupons = UserCoupon::with('coupon')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $availableCoupons = [];
        $usedCoupons = [];
        $expiredCoupons = [];

        foreach ($userCoupons as $userCoupon) {
            $coupon = $userCoupon->coupon;
            
            if ($userCoupon->is_used) {
                $usedCoupons[] = [
                    'id' => $userCoupon->id,
                    'coupon' => $coupon,
                    'used_at' => $userCoupon->used_at,
                    'order_id' => $userCoupon->order_id
                ];
            } elseif (!$coupon->isValid()) {
                $expiredCoupons[] = [
                    'id' => $userCoupon->id,
                    'coupon' => $coupon,
                    'claimed_at' => $userCoupon->created_at
                ];
            } else {
                $availableCoupons[] = [
                    'id' => $userCoupon->id,
                    'coupon' => $coupon,
                    'claimed_at' => $userCoupon->created_at
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'available' => $availableCoupons,
                'used' => $usedCoupons,
                'expired' => $expiredCoupons
            ]
        ]);
    }

    /**
     * 取得可領取的優惠券列表（前台用）
     */
    public function claimableCoupons(Request $request)
    {
        $user = Auth::user();
        
        // 獲取所有啟用的優惠券
        $coupons = Coupon::where('is_active', true)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        $claimableCoupons = [];
        $claimedCoupons = [];

        foreach ($coupons as $coupon) {
            // 檢查用戶是否已經領取過
            $userCoupon = UserCoupon::where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->first();

            if ($userCoupon) {
                // 用戶已領取
                $claimedCoupons[] = [
                    'coupon' => $coupon,
                    'claimed_at' => $userCoupon->created_at,
                    'is_used' => $userCoupon->is_used
                ];
            } else {
                // 用戶可領取
                $claimableCoupons[] = $coupon;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'claimable' => $claimableCoupons,
                'claimed' => $claimedCoupons
            ]
        ]);
    }

    /**
     * 領取優惠券（前台用）
     */
    public function claim(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'coupon_id' => 'required|integer|exists:coupons,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon = Coupon::findOrFail($request->coupon_id);
            
            // 檢查優惠券是否有效
            if (!$coupon->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => '優惠券已停用'
                ], 400);
            }

            if ($coupon->expires_at <= now()) {
                return response()->json([
                    'success' => false,
                    'message' => '優惠券已過期'
                ], 400);
            }

            // 檢查用戶是否已經領取過
            $existingUserCoupon = UserCoupon::where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->first();

            if ($existingUserCoupon) {
                return response()->json([
                    'success' => false,
                    'message' => '您已經領取過此優惠券'
                ], 400);
            }

            // 檢查領取限制
            $totalClaimed = UserCoupon::where('coupon_id', $coupon->id)->count();
            if ($coupon->usage_limit && $totalClaimed >= $coupon->usage_limit) {
                return response()->json([
                    'success' => false,
                    'message' => '優惠券已被領取完畢'
                ], 400);
            }

            // 創建用戶優惠券記錄
            UserCoupon::create([
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'is_used' => false
            ]);

            // 記錄操作日誌
            OperationLog::create([
                'admin_id' => null,
                'action' => 'claim_coupon',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent() ?? null,
                'data' => [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'coupon_id' => $coupon->id,
                    'coupon_name' => $coupon->name
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => '優惠券領取成功！',
                'coupon' => $coupon
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '領取失敗：' . $e->getMessage()
            ], 500);
        }
    }
} 