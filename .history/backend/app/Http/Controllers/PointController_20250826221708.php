<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PointController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            // 使用 auth:sanctum 守衛獲取認證用戶
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => '請先登入'
                ], 401);
            }

            // 分頁參數
            $pageSize = intval($request->input('pageSize', 20));
            $page = intval($request->input('page', 1));

            // 獲取點數交易記錄
            $query = $user->pointTransactions()->orderBy('created_at', 'desc');
            $history = $query->paginate($pageSize, ['*'], 'page', $page);

            // 累積獲得與已使用
            $totalEarned = $user->pointTransactions()->where('type', 'earn')->sum('points');
            $totalUsed = abs($user->pointTransactions()->where('type', 'spend')->sum('points'));

            return response()->json([
                'success' => true,
                'data' => [
                    'current_points' => $user->points ?? 0,
                    'total_earned' => $totalEarned,
                    'total_used' => $totalUsed,
                    'history' => $history->items(),
                    'pagination' => [
                        'current_page' => $history->currentPage(),
                        'last_page' => $history->lastPage(),
                        'per_page' => $history->perPage(),
                        'total' => $history->total(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('獲取用戶點數失敗: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '獲取點數失敗',
                'error' => config('app.debug') ? $e->getMessage() : '系統錯誤'
            ], 500);
        }
    }

    public function earn(Request $request): JsonResponse
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => '請先登入'
                ], 401);
            }

            $data = $request->validate([
                'amount' => 'required|integer|min:1',
                'description' => 'nullable|string'
            ]);

            // 增加用戶點數
            $user->increment('points', $data['amount']);

            // 記錄點數交易
            $user->pointTransactions()->create([
                'points' => $data['amount'],
                'type' => 'earn',
                'description' => $data['description'] ?? '點數獲得'
            ]);

            return response()->json([
                'success' => true,
                'message' => '點數已累積',
                'data' => [
                    'amount' => $data['amount'],
                    'current_points' => $user->fresh()->points
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('累積點數失敗: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '累積點數失敗',
                'error' => config('app.debug') ? $e->getMessage() : '系統錯誤'
            ], 500);
        }
    }

    public function spend(Request $request): JsonResponse
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => '請先登入'
                ], 401);
            }

            $data = $request->validate([
                'amount' => 'required|integer|min:1',
                'description' => 'nullable|string'
            ]);

            // 檢查點數是否足夠
            if ($user->points < $data['amount']) {
                return response()->json([
                    'success' => false,
                    'message' => '點數不足',
                    'data' => [
                        'required' => $data['amount'],
                        'available' => $user->points
                    ]
                ], 400);
            }

            // 扣除用戶點數
            $user->decrement('points', $data['amount']);

            // 記錄點數交易（負數）
            $user->pointTransactions()->create([
                'points' => -$data['amount'],
                'type' => 'spend',
                'description' => $data['description'] ?? '點數使用'
            ]);

            return response()->json([
                'success' => true,
                'message' => '點數已扣除',
                'data' => [
                    'amount' => $data['amount'],
                    'current_points' => $user->fresh()->points
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('使用點數失敗: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '使用點數失敗',
                'error' => config('app.debug') ? $e->getMessage() : '系統錯誤'
            ], 500);
        }
    }
} 