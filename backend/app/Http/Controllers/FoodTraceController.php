<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FoodTraceController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $query = $request->input('query');
        // 假資料，實際應查資料庫
        if ($query === '黃金經典豬肉條') {
            $data = [
                'name' => '黃金經典豬肉條',
                'ingredients' => '豬肉、糖、鹽、醬油',
                'origin' => '台灣台東',
                'allergens' => '大豆、小麥',
                'mfg_date' => '2025-01-01',
                'exp_date' => '2025-07-01',
            ];
        } else {
            $data = null;
        }
        return response()->json([
            'success' => (bool)$data,
            'data' => $data,
        ]);
    }
} 