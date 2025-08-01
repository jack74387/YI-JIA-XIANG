<?php
// 測試 Cloudinary 配置的簡單 API
Route::get('/test-cloudinary-config', function () {
    try {
        $cloudinaryUrl = env('CLOUDINARY_URL');
        
        if (!$cloudinaryUrl) {
            return response()->json([
                'success' => false,
                'message' => 'CLOUDINARY_URL 未設定'
            ]);
        }
        
        // 解析 URL
        $urlParts = parse_url($cloudinaryUrl);
        if (!$urlParts || !isset($urlParts['user']) || !isset($urlParts['pass']) || !isset($urlParts['host'])) {
            return response()->json([
                'success' => false,
                'message' => '無法解析 CLOUDINARY_URL'
            ]);
        }
        
        $apiKey = $urlParts['user'];
        $apiSecret = $urlParts['pass'];
        $cloudName = $urlParts['host'];
        
        // 測試 cloudinary() helper
        try {
            $cloudinary = cloudinary();
            $config = $cloudinary->getConfiguration();
            
            return response()->json([
                'success' => true,
                'message' => 'Cloudinary 配置正常',
                'data' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'cloudinary_helper' => 'OK',
                    'config_cloud_name' => $config->cloud['cloud_name'] ?? null,
                    'config_api_key' => $config->cloud['api_key'] ?? null
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cloudinary helper 失敗: ' . $e->getMessage(),
                'data' => [
                    'parsed_cloud_name' => $cloudName,
                    'parsed_api_key' => $apiKey
                ]
            ]);
        }
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => '測試失敗: ' . $e->getMessage()
        ]);
    }
});
