<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        $points = 120;
        $history = [
            ['id' => 1, 'date' => '2025-01-10', 'desc' => '購物獲得', 'change' => 50],
            ['id' => 2, 'date' => '2025-01-15', 'desc' => '評論加碼', 'change' => 20],
            ['id' => 3, 'date' => '2025-01-20', 'desc' => '兌換折抵', 'change' => -30],
        ];
        return response()->json(['success' => true, 'points' => $points, 'history' => $history]);
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