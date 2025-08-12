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
        $heroBgImage = Setting::where('key', 'hero_bg_image')->value('value');

        return response()->json([
            'success' => true,
            'data' => [
                'hero_bg_image' => $heroBgImage
            ]
        ]);
    }

    /**
     * 上傳首頁背景圖
     */
    public function uploadHeroBgImage(Request $request)
    {
        \Log::info('收到請求: ', $request->all());

        $request->validate([
            'image' => 'required|image|max:10240' // 最大10MB
        ]);

        try {
            $file = $request->file('image');

            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            // 使用 Upload Preset 上傳背景圖片
            $uploadResult = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'upload_preset' => 'yijiaxiang', // 使用相同的 Upload Preset
                'resource_type' => 'image',
                'folder' => 'yijiaxiang/hero_backgrounds',
                'transformation' => [
                    'width' => 1920,
                    'height' => 1080,
                    'crop' => 'fill'
                ]
            ]);

            // 保存圖片 URL 到資料庫或配置文件
            Setting::updateOrCreate(
                ['key' => 'hero_bg_image'],
                ['value' => $uploadResult['secure_url']]
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'hero_bg_image' => $uploadResult['secure_url']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('背景圖片上傳失敗: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '背景圖片上傳失敗: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 刪除首頁背景圖
     */
    public function deleteHeroBgImage()
    {
        try {
            // 從資料庫中獲取背景圖片的 public_id
            $heroBgImageUrl = Setting::where('key', 'hero_bg_image')->value('value');
            if (!$heroBgImageUrl) {
                return response()->json([
                    'success' => false,
                    'message' => '未找到背景圖片的 URL'
                ], 404);
            }

            // 提取 public_id
            $publicId = basename(parse_url($heroBgImageUrl, PHP_URL_PATH), '.' . pathinfo($heroBgImageUrl, PATHINFO_EXTENSION));

            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            // 刪除圖片
            $cloudinary->uploadApi()->destroy($publicId);

            // 刪除資料庫中的記錄
            Setting::where('key', 'hero_bg_image')->delete();

            return response()->json([
                'success' => true,
                'message' => '背景圖片刪除成功'
            ]);
        } catch (\Exception $e) {
            \Log::error('背景圖片刪除失敗: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '背景圖片刪除失敗: ' . $e->getMessage()
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
