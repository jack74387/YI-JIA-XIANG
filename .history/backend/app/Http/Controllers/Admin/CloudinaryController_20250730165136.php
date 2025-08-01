<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CloudinaryController extends Controller
{
    public function getSignature(Request $request)
    {
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
        $cloudName = $urlParts['host']; // 從 URL 中的 host 部分取得 cloud_name

        $timestamp = time();
        $folder = 'yijiaxiang';

        $paramsToSign = [
            "timestamp" => $timestamp,
            "folder" => $folder,
        ];
        
        ksort($paramsToSign);
        $stringToSign = http_build_query($paramsToSign);
        $stringToSign = urldecode($stringToSign);
        $signature = sha1($stringToSign . $apiSecret);

        return response()->json([
            'success' => true,
            'signature' => $signature,
            'timestamp' => $timestamp,
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'folder' => $folder,
        ]);
    }
}