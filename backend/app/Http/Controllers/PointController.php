<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => '未登入'], 401);
        }
        $points = $user->points;
        // 分頁參數
        $pageSize = intval($request->input('pageSize', 20));
        $page = intval($request->input('page', 1));
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