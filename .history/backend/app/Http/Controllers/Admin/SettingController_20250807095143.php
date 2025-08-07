<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Transformation;

class SettingController extends Controller
{
    /**
     * 獲取所有設定
     */
    public function index()
    {
        try {
            $settings = Setting::all()->keyBy('key');
            
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '獲取設定失敗: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 獲取首頁背景圖
     */
    public function getHeroBgImage()
    {
        try {
            $bgImage = Setting::getHeroBgImage();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'hero_bg_image' => $bgImage
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '獲取背景圖失敗: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 上傳首頁背景圖
     */
    public function uploadHeroBgImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240' // 最大10MB
        ]);

        try {
            $file = $request->file('image');
            
            // Cloudinary 配置
            $config = [
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true
                ]
            ];

            $cloudinary = new Cloudinary($config);
            
            // 上傳到 Cloudinary
            $uploadResult = $cloudinary->uploadApi()->upload($file->getPathname(), [
                'folder' => 'yijiaxiang/hero_backgrounds',
                'public_id' => 'hero_bg_' . time(),
                'overwrite' => true,
                'transformation' => [
                    'width' => 1920,
                    'height' => 1080,
                    'crop' => 'fill',
                    'quality' => 'auto:good',
                    'format' => 'webp'
                ]
            ]);

            // 儲存設定
            Setting::setHeroBgImage($uploadResult['secure_url']);

            return response()->json([
                'success' => true,
                'message' => '背景圖上傳成功',
                'data' => [
                    'hero_bg_image' => $uploadResult['secure_url']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '上傳失敗: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 刪除首頁背景圖
     */
    public function deleteHeroBgImage()
    {
        try {
            $currentImage = Setting::getHeroBgImage();
            
            if ($currentImage) {
                // 從 Cloudinary URL 提取 public_id
                $publicId = $this->extractPublicIdFromUrl($currentImage);
                
                if ($publicId) {
                    // Cloudinary 配置
                    $config = [
                        'cloud' => [
                            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                            'api_key' => env('CLOUDINARY_API_KEY'),
                            'api_secret' => env('CLOUDINARY_API_SECRET'),
                        ]
                    ];

                    $cloudinary = new Cloudinary($config);
                    
                    // 刪除 Cloudinary 上的圖片
                    $cloudinary->uploadApi()->destroy($publicId);
                }
            }

            // 清除設定
            Setting::setHeroBgImage(null);

            return response()->json([
                'success' => true,
                'message' => '背景圖刪除成功'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '刪除失敗: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 從 Cloudinary URL 提取 public_id
     */
    private function extractPublicIdFromUrl($url)
    {
        $pattern = '/\/v\d+\/(.+)\.(jpg|jpeg|png|gif|webp)/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
