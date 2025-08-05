<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $articles = Article::orderByDesc('published_at')->paginate(20);
        return response()->json(['success' => true, 'data' => $articles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'images_public_ids' => 'nullable|array',
            'images_public_ids.*' => 'string',
            'videos' => 'nullable|array',
            'videos.*' => 'string',
            'videos_public_ids' => 'nullable|array',
            'videos_public_ids.*' => 'string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);
        $article = Article::create(array_merge($validated, [
            'user_id' => $user->id,
        ]));
        return response()->json(['success' => true, 'data' => $article]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $article = Article::findOrFail($id);
        return response()->json(['success' => true, 'data' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $article = Article::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'images_public_ids' => 'nullable|array',
            'images_public_ids.*' => 'string',
            'videos' => 'nullable|array',
            'videos.*' => 'string',
            'videos_public_ids' => 'nullable|array',
            'videos_public_ids.*' => 'string',
            'status' => 'sometimes|required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);
        $article->update($validated);
        return response()->json(['success' => true, 'data' => $article]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        
        $article = Article::findOrFail($id);
        
        try {
            // 刪除文章相關的 Cloudinary 資源
            $this->deleteArticleCloudinaryAssets($article);
            
            // 刪除文章記錄
            $article->delete();
            
            \Log::info('文章刪除成功', [
                'article_id' => $id,
                'title' => $article->title,
                'user_id' => $user->id
            ]);
            
            return response()->json(['success' => true, 'message' => '文章及相關圖片已刪除']);
            
        } catch (\Exception $e) {
            \Log::error('刪除文章失敗', [
                'article_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => '刪除文章失敗: ' . $e->getMessage()
            ], 500);
        }
    }

    // 圖片上傳 - 使用 Cloudinary Upload Presets (无权限检查)
    public function uploadImage(Request $request)
    {
        // 移除权限检查，允许所有用户上传
        \Log::info('文章圖片上傳請求', ['user_id' => $request->user()?->id ?? 'anonymous']);
        
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB
        ]);
        
        try {
            $file = $request->file('image');
            
            // 使用新的 API 密钥配置 Cloudinary
            $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: env('CLOUDINARY_CLOUD_NAME', 'daeb3goxf');
            $apiKey = getenv('CLOUDINARY_API_KEY') ?: env('CLOUDINARY_API_KEY', '697592912781924');
            $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: env('CLOUDINARY_API_SECRET', '20hBk7nMJHVu856JQShTuuDkwyw');
            
            if (!$cloudName || !$apiKey || !$apiSecret) {
                \Log::error('Cloudinary 配置缺失 (文章圖片)', [
                    'cloud_name' => $cloudName ? '已設置' : '未設置',
                    'api_key' => $apiKey ? '已設置' : '未設置',
                    'api_secret' => $apiSecret ? '已設置' : '未設置'
                ]);
                return response()->json(['success' => false, 'message' => 'Cloudinary 配置缺失'], 500);
            }
            
            // 建立 Cloudinary 實例
            $config = [
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret
                ]
            ];
            
            $cloudinary = new \Cloudinary\Cloudinary($config);
            
            // 使用 Upload Preset 上傳文章圖片
            $uploadedFile = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'upload_preset' => 'yijiaxiang', // 使用相同的 Upload Preset
                'resource_type' => 'image',
                'folder' => 'yijiaxiang/articles', // Upload Preset 中可能已設置，這裡作為備用
                'context' => [
                    'alt' => 'Article image for 一佳香',
                    'caption' => 'Article image uploaded from admin panel'
                ],
                'tags' => ['article', 'yijiaxiang', 'admin-upload']
            ]);
            
            $url = $uploadedFile['secure_url'];
            
            \Log::info('文章圖片上傳成功到 Cloudinary (Upload Preset)', [
                'user_id' => $request->user()?->id ?? 'anonymous',
                'file_name' => $file->getClientOriginalName(),
                'cloudinary_url' => $url,
                'public_id' => $uploadedFile['public_id'],
                'upload_preset' => 'yijiaxiang',
                'file_size' => $file->getSize(),
                'format' => $uploadedFile['format'] ?? null
            ]);
            
            return response()->json([
                'success' => true, 
                'url' => $url,
                'public_id' => $uploadedFile['public_id'],
                'format' => $uploadedFile['format'] ?? null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('文章圖片上傳到 Cloudinary 失敗 (Upload Preset)', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id ?? 'anonymous',
                'upload_preset' => 'yijiaxiang'
            ]);
            return response()->json(['success' => false, 'message' => '圖片上傳失敗: ' . $e->getMessage()], 500);
        }
    }

        // 影片上傳 - 使用 Cloudinary Upload Presets (无权限检查)
    public function uploadVideo(Request $request)
    {
        // 移除权限检查，允许所有用户上传
        \Log::info('文章影片上傳請求', ['user_id' => $request->user()?->id ?? 'anonymous']);
        
        $request->validate([
            'video' => 'required|mimes:mp4,avi,mov,wmv,flv|max:51200', // 50MB
        ]);
        
        try {
            $file = $request->file('video');
            
            // 使用新的 API 密钥配置 Cloudinary
            $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: env('CLOUDINARY_CLOUD_NAME', 'daeb3goxf');
            $apiKey = getenv('CLOUDINARY_API_KEY') ?: env('CLOUDINARY_API_KEY', '697592912781924');
            $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: env('CLOUDINARY_API_SECRET', '20hBk7nMJHVu856JQShTuuDkwyw');
            
            if (!$cloudName || !$apiKey || !$apiSecret) {
                \Log::error('Cloudinary 配置缺失 (文章影片)', [
                    'cloud_name' => $cloudName ? '已設置' : '未設置',
                    'api_key' => $apiKey ? '已設置' : '未設置',
                    'api_secret' => $apiSecret ? '已設置' : '未設置'
                ]);
                return response()->json(['success' => false, 'message' => 'Cloudinary 配置缺失'], 500);
            }
            
            // 建立 Cloudinary 實例
            $config = [
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret
                ]
            ];
            
            $cloudinary = new \Cloudinary\Cloudinary($config);
            
            // 使用 Upload Preset 上傳文章影片
            $uploadedFile = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'upload_preset' => 'yijiaxiang', // 使用相同的 Upload Preset
                'resource_type' => 'video',
                'folder' => 'yijiaxiang/articles/videos', // Upload Preset 中可能已設置，這裡作為備用
                'context' => [
                    'alt' => 'Article video for 一佳香',
                    'caption' => 'Article video uploaded from admin panel'
                ],
                'tags' => ['article', 'video', 'yijiaxiang', 'admin-upload']
            ]);
            
            $url = $uploadedFile['secure_url'];
            
            \Log::info('文章影片上傳成功到 Cloudinary (Upload Preset)', [
                'user_id' => $request->user()?->id ?? 'anonymous',
                'file_name' => $file->getClientOriginalName(),
                'cloudinary_url' => $url,
                'public_id' => $uploadedFile['public_id'],
                'upload_preset' => 'yijiaxiang',
                'file_size' => $file->getSize(),
                'format' => $uploadedFile['format'] ?? null,
                'duration' => $uploadedFile['duration'] ?? null
            ]);
            
            return response()->json([
                'success' => true, 
                'url' => $url,
                'public_id' => $uploadedFile['public_id'],
                'format' => $uploadedFile['format'] ?? null,
                'duration' => $uploadedFile['duration'] ?? null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('文章影片上傳到 Cloudinary 失敗 (Upload Preset)', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id ?? 'anonymous',
                'upload_preset' => 'yijiaxiang'
            ]);
            return response()->json(['success' => false, 'message' => '影片上傳失敗: ' . $e->getMessage()], 500);
        }
    }

    // 刪除圖片
    public function deleteImage(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $request->validate(['url' => 'required|string']);
        $url = $request->input('url');
        $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => '檔案不存在'], 404);
    }

    // 刪除影片
    public function deleteVideo(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $request->validate(['url' => 'required|string']);
        $url = $request->input('url');
        $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => '檔案不存在'], 404);
    }

    // 一鍵發布到 Facebook
    public function publishToFacebook(Request $request, $id)
    {
        try {
            $user = $request->user();
            if (!$user || !$user->is_admin) {
                return response()->json(['success' => false, 'message' => '無權限'], 403);
            }
            
            $article = Article::findOrFail($id);
            if ($article->status !== 'published') {
                return response()->json(['success' => false, 'message' => '僅能發布已發布的文章'], 400);
            }
            
            // Facebook API 設定
            $fbPageId = env('FACEBOOK_PAGE_ID');
            $fbToken = env('FACEBOOK_PAGE_ACCESS_TOKEN');
            
            if (!$fbPageId || !$fbToken) {
                return response()->json(['success' => false, 'message' => 'Facebook 設定不完整'], 500);
            }
            
            // 記錄環境變數用於除錯
            \Log::info('Facebook Environment Variables', [
                'page_id' => $fbPageId,
                'token_length' => strlen($fbToken),
                'token_start' => substr($fbToken, 0, 20) . '...'
            ]);
            
            // 準備發布內容
            $message = $article->title . "\n\n" . strip_tags($article->content);
            $link = url('/articles/' . $article->id);
            
            $params = [
                'message' => $message . "\n\n" . $link,
            ];
            
            $fbApi = "https://graph.facebook.com/{$fbPageId}/feed";
            
            // 記錄請求參數用於除錯
            \Log::info('Facebook API Request', [
                'url' => $fbApi,
                'params' => $params,
                'page_id' => $fbPageId
            ]);
            
            // 發送請求到 Facebook
            $response = \Http::withToken($fbToken)
                ->withOptions(['verify' => false]) // 禁用 SSL 驗證
                ->post($fbApi, $params);
                
            // 記錄回應用於除錯
            \Log::info('Facebook API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json()
            ]);
            
            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => '已發布到 Facebook']);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Facebook 發布失敗', 
                    'fb_response' => $response->json(),
                    'status' => $response->status(),
                    'body' => $response->body()
                ], 500);
            }
            
        } catch (\Exception $e) {
            \Log::error('Facebook publish error: ' . $e->getMessage());
            \Log::error('Facebook publish error trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false, 
                'message' => '發布過程中發生錯誤: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 通過 public_id 刪除 Cloudinary 圖片/影片
     */
    public function deleteCloudinaryById(Request $request)
    {
        try {
            $request->validate([
                'public_id' => 'required|string'
            ]);

            $publicId = $request->input('public_id');
            
            \Log::info('嘗試刪除文章 Cloudinary 資源', ['public_id' => $publicId]);

            // 使用新的 API 密钥配置 Cloudinary
            $cloudName = getenv('CLOUDINARY_CLOUD_NAME') ?: env('CLOUDINARY_CLOUD_NAME', 'daeb3goxf');
            $apiKey = getenv('CLOUDINARY_API_KEY') ?: env('CLOUDINARY_API_KEY', '697592912781924');
            $apiSecret = getenv('CLOUDINARY_API_SECRET') ?: env('CLOUDINARY_API_SECRET', '20hBk7nMJHVu856JQShTuuDkwyw');

            if (!$cloudName || !$apiKey || !$apiSecret) {
                return response()->json(['success' => false, 'message' => 'Cloudinary 配置缺失'], 500);
            }

            // 建立 Cloudinary 實例
            $config = [
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret
                ]
            ];

            $cloudinary = new \Cloudinary\Cloudinary($config);

            // 刪除資源（會自動檢測是圖片還是影片）
            $result = $cloudinary->adminApi()->deleteAssets([$publicId]);

            \Log::info('Cloudinary 文章資源刪除結果', [
                'public_id' => $publicId,
                'result' => $result
            ]);

            return response()->json([
                'success' => true,
                'message' => '資源刪除成功',
                'public_id' => $publicId,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            \Log::error('刪除文章 Cloudinary 資源失敗', [
                'public_id' => $request->input('public_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '刪除失敗: ' . $e->getMessage()
            ], 500);
        }
    }

}
