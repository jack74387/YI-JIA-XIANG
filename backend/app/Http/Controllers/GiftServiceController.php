<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GiftServiceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gift_wrap' => 'boolean',
            'card' => 'boolean',
            'card_message' => 'nullable|string|max:50',
        ]);
        // 實際應儲存於訂單或加值服務表，這裡僅回傳成功
        return response()->json([
            'success' => true,
            'message' => '加值服務已儲存',
            'data' => $validated,
        ]);
    }
} 