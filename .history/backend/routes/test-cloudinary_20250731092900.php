<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 簡單的 Cloudinary 測試端點
Route::get('/test-cloudinary-simple', function () {
    try {
        // 檢查環境變數
        $cloudinaryUrl = env('CLOUDINARY_URL');
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
        $result = [
            'success' => true,
            'message' => 'Cloudinary 環境變數檢查',
            'config' => [
                'cloudinary_url_set' => !empty($cloudinaryUrl),
                'cloud_name' => $cloudName,
                'api_key' => $apiKey ? substr($apiKey, 0, 6) . '...' : null,
                'api_secret_set' => !empty($apiSecret),
            ]
        ];
        
        // 嘗試檢查 Cloudinary 類別是否存在
        if (class_exists('\Cloudinary\Cloudinary')) {
            $result['cloudinary_class_exists'] = true;
            
            try {
                // 嘗試建立 Cloudinary 實例
                $config = [
                    'cloud' => [
                        'cloud_name' => $cloudName,
                        'api_key' => $apiKey,
                        'api_secret' => $apiSecret
                    ]
                ];
                
                $cloudinary = new \Cloudinary\Cloudinary($config);
                $result['cloudinary_instance_created'] = true;
                
            } catch (\Exception $e) {
                $result['cloudinary_instance_error'] = $e->getMessage();
            }
            
        } else {
            $result['cloudinary_class_exists'] = false;
        }
        
        return response()->json($result);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => '測試失敗',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
