<?php

use Illuminate\Support\Facades\Route;

// 直接测试 PHP 环境变量
Route::get('/test-env-direct', function () {
    try {
        // 使用 $_ENV 和 getenv() 直接访问环境变量
        $result = [
            'success' => true,
            'message' => '直接环境变量检查',
            'env_direct' => [
                'CLOUDINARY_URL_getenv' => getenv('CLOUDINARY_URL'),
                'CLOUDINARY_CLOUD_NAME_getenv' => getenv('CLOUDINARY_CLOUD_NAME'),
                'CLOUDINARY_API_KEY_getenv' => getenv('CLOUDINARY_API_KEY'),
                'CLOUDINARY_API_SECRET_getenv' => getenv('CLOUDINARY_API_SECRET'),
            ],
            'env_superglobal' => [
                'CLOUDINARY_URL_ENV' => $_ENV['CLOUDINARY_URL'] ?? 'not set',
                'CLOUDINARY_CLOUD_NAME_ENV' => $_ENV['CLOUDINARY_CLOUD_NAME'] ?? 'not set',
                'CLOUDINARY_API_KEY_ENV' => $_ENV['CLOUDINARY_API_KEY'] ?? 'not set',
                'CLOUDINARY_API_SECRET_ENV' => $_ENV['CLOUDINARY_API_SECRET'] ?? 'not set',
            ],
            'laravel_env' => [
                'CLOUDINARY_URL_laravel' => env('CLOUDINARY_URL'),
                'CLOUDINARY_CLOUD_NAME_laravel' => env('CLOUDINARY_CLOUD_NAME'),
                'CLOUDINARY_API_KEY_laravel' => env('CLOUDINARY_API_KEY'),
                'CLOUDINARY_API_SECRET_laravel' => env('CLOUDINARY_API_SECRET'),
            ]
        ];
        
        // 如果 getenv() 能获取到值，尝试创建 Cloudinary 实例
        $cloudName = getenv('CLOUDINARY_CLOUD_NAME');
        $apiKey = getenv('CLOUDINARY_API_KEY');
        $apiSecret = getenv('CLOUDINARY_API_SECRET');
        
        if ($cloudName && $apiKey && $apiSecret) {
            $config = [
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret
                ]
            ];
            
            $cloudinary = new \Cloudinary\Cloudinary($config);
            $result['cloudinary_test'] = 'Cloudinary instance created successfully with getenv()';
        } else {
            $result['cloudinary_test'] = 'Unable to create Cloudinary instance - missing env vars';
        }
        
        return response()->json($result);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
