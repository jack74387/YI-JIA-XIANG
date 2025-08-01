<?php

return [
    'cloud_url' => env('CLOUDINARY_URL'),
    
    // 從 CLOUDINARY_URL 解析的配置
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    
    // 預設上傳設定
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    'secure' => true,
    
    // 預設變換
    'default_transformations' => [
        'quality' => 'auto',
        'fetch_format' => 'auto',
    ]
];
