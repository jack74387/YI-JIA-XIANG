<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Order;
use App\Models\PointTransaction;

class MemberController extends Controller
{
    /**
     * 取得會員資料
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        // 確保返回的用戶資料包含 avatar_url
        $userData = $user->toArray();
        $userData['avatar_url'] = $user->avatar_url;
        
        return response()->json([
            'success' => true,
            'user' => $userData
        ]);
    }

    /**
     * 更新會員資料
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthday' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'email_notifications' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update($request->only([
                'name', 'phone', 'address', 'birthday', 'gender',
                'email_notifications'
            ]));

            $updatedUser = $user->fresh();
            $userData = $updatedUser->toArray();
            $userData['avatar_url'] = $updatedUser->avatar_url;
            
            return response()->json([
                'success' => true,
                'message' => '個人資料更新成功',
                'user' => $userData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '更新失敗，請稍後再試'
            ], 500);
        }
    }

    /**
     * 修改密碼
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        // 驗證目前密碼
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => '目前密碼錯誤'
            ], 400);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => '密碼修改成功'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '密碼修改失敗，請稍後再試'
            ], 500);
        }
    }

    /**
     * 上傳頭像
     */
    public function uploadAvatar(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請上傳有效的圖片檔案',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 刪除舊頭像
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // 儲存新頭像
            $path = $request->file('avatar')->store('avatars', 'public');
            
            $user->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => '頭像上傳成功',
                'avatar_url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '頭像上傳失敗：' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 取得會員統計資料
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('final_amount');
        $currentPoints = $user->points;
        $memberLevel = $user->member_level_name;
        
        // 最近訂單
        $recentOrders = $user->orders()
            ->with('items')
            ->latest()
            ->limit(5)
            ->get();

        // 點數交易記錄
        $pointTransactions = $user->pointTransactions()
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'current_points' => $currentPoints,
                'member_level' => $memberLevel,
                'member_level_color' => $user->member_level_color,
                'is_premium' => $user->isPremiumMember(),
            ],
            'recent_orders' => $recentOrders,
            'point_transactions' => $pointTransactions
        ]);
    }

    /**
     * 取得會員訂單列表
     */
    public function orders(Request $request)
    {
        $user = $request->user();
        
        $orders = $user->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    /**
     * 取得點數交易記錄
     */
    public function pointHistory(Request $request)
    {
        $user = $request->user();
        
        $transactions = $user->pointTransactions()
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    /**
     * 刪除帳戶
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'confirmation' => 'required|in:DELETE'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請確認刪除操作',
                'errors' => $validator->errors()
            ], 422);
        }

        // 驗證密碼
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => '密碼錯誤'
            ], 400);
        }

        try {
            // 刪除用戶的所有資料
            $user->orders()->delete();
            $user->pointTransactions()->delete();
            $user->cart()->delete();
            $user->tokens()->delete();
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => '帳戶已成功刪除'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '刪除失敗，請稍後再試'
            ], 500);
        }
    }
}
