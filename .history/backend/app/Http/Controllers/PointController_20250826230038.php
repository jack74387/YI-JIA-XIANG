<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => '請先登入以查看點數資訊'
                ], 401);
            }

            $points = $user->points ?? 0;
            
            // 分頁參數
            $pageSize = intval($request->input('pageSize', 20));
            $page = intval($request->input('page', 1));
            
            // 檢查是否有點數交易記錄
            $query = $user->pointTransactions()->orderBy('created_at', 'desc');
            $history = $query->paginate($pageSize, ['*'], 'page', $page);
            
            // 累積獲得與已使用
            $totalEarned = $user->pointTransactions()->where('type', 'earn')->sum('points');
            $totalUsed = abs($user->pointTransactions()->where('type', 'spend')->sum('points'));
            
            return response()->json([
                'success' => true,
                'points' => $points,
                'history' => $history->items(),
                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'last_page' => $history->lastPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                ],
                'total_earned' => $totalEarned,
                'total_used' => $totalUsed
            ]);

        } catch (\Exception $e) {
            \Log::error('Points fetch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '獲取點數資訊時發生錯誤',
                'error' => config('app.debug') ? $e->getMessage() : '內部錯誤'
            ], 500);
        }
    }
    public function earn(Request $request)
    {
        $data = $request->validate(['amount' => 'required|integer']);
        // 實際應累積點數
        return response()->json(['success' => true, 'message' => '點數已累積', 'amount' => $data['amount']]);
    }
    public function spend(Request $request)
    {
        $data = $request->validate(['amount' => 'required|integer']);
        // 實際應扣除點數
        return response()->json(['success' => true, 'message' => '點數已扣除', 'amount' => $data['amount']]);
    }
} 