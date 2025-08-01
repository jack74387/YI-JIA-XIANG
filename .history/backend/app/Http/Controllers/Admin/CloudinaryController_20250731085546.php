<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CloudinaryController extends Controller
{
    public function getSignature(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => '無權限'], 403);
            }

            $cloudinaryUrl = env('CLOUDINARY_URL');

            if (!$cloudinaryUrl) {
                return response()->json(['success' => false, 'message' => 'CLOUDINARY_URL not set in .env file.'], 500);
            }

            // Parse the URL to get the credentials
            $urlParts = parse_url($cloudinaryUrl);
            if (!$urlParts || !isset($urlParts['user']) || !isset($urlParts['pass']) || !isset($urlParts['host'])) {
                return response()->json(['success' => false, 'message' => 'Could not parse Cloudinary credentials from URL.'], 500);
            }

            $apiKey = $urlParts['user'];
            $apiSecret = $urlParts['pass'];
            $cloudName = $urlParts['host'];

            $timestamp = time();
            $folder = 'yijiaxiang';

            // 建立簽名參數（必須按字母順序排列）
            $paramsToSign = [
                "folder" => $folder,
                "timestamp" => $timestamp,
            ];
            
            // 建立簽名字串
            $signatureString = '';
            foreach ($paramsToSign as $key => $value) {
                $signatureString .= $key . '=' . $value . '&';
            }
            $signatureString = rtrim($signatureString, '&'); // 移除最後的 &
            
            // 產生簽名
            $signature = sha1($signatureString . $apiSecret);

            \Log::info('Cloudinary 簽名產生', [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'folder' => $folder,
                'signature_string' => $signatureString,
                'signature' => $signature
            ]);

            return response()->json([
                'success' => true,
                'signature' => $signature,
                'timestamp' => $timestamp,
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'folder' => $folder,
            ]);

        } catch (\Exception $e) {
            \Log::error('Cloudinary 簽名產生失敗', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => '簽名產生失敗: ' . $e->getMessage()], 500);
        }
    }

    // 測試 Cloudinary 連接
    public function testConnection(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => '無權限'], 403);
            }

            // 測試 Cloudinary 配置
            $cloudinary = cloudinary();
            $config = $cloudinary->getConfiguration();
            
            return response()->json([
                'success' => true,
                'message' => 'Cloudinary 連接正常',
                'config' => [
                    'cloud_name' => $config->cloud['cloud_name'] ?? null,
                    'api_key' => $config->cloud['api_key'] ?? null,
                    'api_secret_set' => !empty($config->cloud['api_secret'])
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Cloudinary 連接測試失敗', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Cloudinary 連接失敗: ' . $e->getMessage()
            ], 500);
        }
    }
}