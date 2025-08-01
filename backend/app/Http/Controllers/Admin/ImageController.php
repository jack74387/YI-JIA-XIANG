<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cloudinary\Api\ApiUtils;

class ImageController extends Controller
{
    public function getCloudinarySignature(Request $request)
    {
        $timestamp = time();
        // 您可以加入更多參數，例如上傳資料夾
        $paramsToSign = [
            'timestamp' => $timestamp,
            'folder' => 'yijiaxiang' // 指定上傳到 Cloudinary 的哪個資料夾
        ];

        // 使用您的 API Secret 產生簽章
        $signature = ApiUtils::signParameters($paramsToSign, config('cloudinary.api_secret'));

        return response()->json([
            'success' => true,
            'signature' => $signature,
            'timestamp' => $timestamp,
            'api_key' => config('cloudinary.api_key'),
            'cloud_name' => config('cloudinary.cloud_name'),
        ]);
    }
}
