<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserAddressController;
use Illuminate\Http\Request;

// 測試路由
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// 測試檔案上傳路由（僅用於調試）
Route::post('/test-upload', function (Request $request) {
    try {
        if (!$request->hasFile('image')) {
            return response()->json(['success' => false, 'message' => '沒有檔案']);
        }
        
        $file = $request->file('image');
        $filename = 'test_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'products/' . $filename;
        
        $stored = \Storage::disk('public')->put($path, file_get_contents($file));
        
        if ($stored) {
            $url = \Storage::disk('public')->url($path);
            return response()->json([
                'success' => true, 
                'url' => $url,
                'path' => $path,
                'storage_path' => storage_path('app/public/' . $path)
            ]);
        } else {
            return response()->json(['success' => false, 'message' => '儲存失敗']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
});

Route::prefix('v1')->group(function () {
    // 公開路由 - 不需要認證
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    
    // 分類相關路由
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    
    // 認證相關路由
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/admin-login', [AuthController::class, 'adminLogin']);
    Route::post('/auth/line-login', [AuthController::class, 'lineLogin']);
    Route::post('/auth/google-login', [AuthController::class, 'googleLogin']);
    Route::post('/auth/facebook-login', [AuthController::class, 'facebookLogin']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

    // 文章（前台）
    Route::get('/articles', [\App\Http\Controllers\ArticleController::class, 'index']);
    Route::get('/articles/{id}', [\App\Http\Controllers\ArticleController::class, 'show']);

    // 需要認證的路由
    Route::middleware('auth:sanctum')->group(function () {
        // 用戶相關
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        
        // 購物車相關
        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart', [CartController::class, 'store']);
        Route::put('cart/{id}', [CartController::class, 'update']);
        Route::delete('cart/{id}', [CartController::class, 'destroy']);

        // 訂單相關
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::get('/user/orders', [OrderController::class, 'userOrders']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        // Route::middleware(['auth:sanctum', \App\Http\Middleware\AdminMiddleware::class])->get('/orders', [OrderController::class, 'adminIndex']);

        // 會員中心相關
        Route::prefix('member')->group(function () {
            Route::get('/profile', [MemberController::class, 'profile']);
            Route::put('/profile', [MemberController::class, 'updateProfile']);
            Route::put('/password', [MemberController::class, 'changePassword']);
            Route::post('/avatar', [MemberController::class, 'uploadAvatar']);
            Route::get('/statistics', [MemberController::class, 'statistics']);
            Route::get('/orders', [MemberController::class, 'orders']);
            Route::get('/points/history', [MemberController::class, 'pointHistory']);
            Route::delete('/account', [MemberController::class, 'deleteAccount']);
        });
    
        // 優惠券相關
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::post('/coupons/redeem', [CouponController::class, 'redeem']);
        Route::post('/coupons/validate', [CouponController::class, 'validate']);
        Route::get('/coupons/user', [CouponController::class, 'userCoupons']);
        Route::get('/coupons/claimable', [CouponController::class, 'claimableCoupons']);
        Route::post('/coupons/claim', [CouponController::class, 'claim']);
    
        // 點數相關
        Route::get('/points', [\App\Http\Controllers\PointController::class, 'index']);
        Route::post('/points/earn', [\App\Http\Controllers\PointController::class, 'earn']);
        Route::post('/points/spend', [\App\Http\Controllers\PointController::class, 'spend']);

        // 常用地址管理
        Route::get('/user-addresses', [UserAddressController::class, 'index']);
        Route::post('/user-addresses', [UserAddressController::class, 'store']);
        Route::put('/user-addresses/{id}', [UserAddressController::class, 'update']);
        Route::delete('/user-addresses/{id}', [UserAddressController::class, 'destroy']);
        Route::post('/user-addresses/{id}/set-default', [UserAddressController::class, 'setDefault']);

        // 文章（後台）
        Route::prefix('admin')->group(function () {
            Route::apiResource('articles', \App\Http\Controllers\Admin\ArticleController::class);
            Route::post('articles/upload-image', [\App\Http\Controllers\Admin\ArticleController::class, 'uploadImage']);
            Route::post('articles/upload-video', [\App\Http\Controllers\Admin\ArticleController::class, 'uploadVideo']);
            Route::post('articles/delete-image', [\App\Http\Controllers\Admin\ArticleController::class, 'deleteImage']);
            Route::post('articles/delete-video', [\App\Http\Controllers\Admin\ArticleController::class, 'deleteVideo']);
            Route::post('articles/{id}/publish-fb', [\App\Http\Controllers\Admin\ArticleController::class, 'publishToFacebook']);
        });

        // 管理員專用
        Route::middleware('auth:sanctum')->group(function () {
            Route::put('/auth/admin/password', [AuthController::class, 'changeAdminPassword']);
            // 多管理員帳號管理 CRUD
            Route::get('/admins', [\App\Http\Controllers\AdminController::class, 'index']);
            Route::post('/admins', [\App\Http\Controllers\AdminController::class, 'store']);
            Route::put('/admins/{id}', [\App\Http\Controllers\AdminController::class, 'update']);
            Route::delete('/admins/{id}', [\App\Http\Controllers\AdminController::class, 'destroy']);
            Route::get('/operation-logs', [\App\Http\Controllers\AdminController::class, 'operationLogs']);
            Route::delete('/operation-logs/{id}', [\App\Http\Controllers\AdminController::class, 'operationLogDestroy']);
            // 後台訂單管理
            Route::get('/admin/orders', [\App\Http\Controllers\OrderController::class, 'adminIndex']);
            Route::get('/admin/orders/export', [\App\Http\Controllers\OrderController::class, 'exportOrders']);
            Route::get('/admin/orders/{id}', [\App\Http\Controllers\OrderController::class, 'adminShow']);
            Route::put('/admin/orders/{id}/status', [\App\Http\Controllers\OrderController::class, 'adminUpdateStatus']);
            
            // 後台會員管理
            Route::get('/admin/members', [\App\Http\Controllers\AdminController::class, 'adminMembers']);
            Route::get('/admin/members/export', [\App\Http\Controllers\AdminController::class, 'exportMembers']);
            Route::get('/admin/members/{id}', [\App\Http\Controllers\AdminController::class, 'adminMemberShow']);
            Route::put('/admin/members/{id}', [\App\Http\Controllers\AdminController::class, 'adminMemberUpdate']);
            Route::post('/admin/members/{id}/points', [\App\Http\Controllers\AdminController::class, 'adminMemberAdjustPoints']);
            
            // 後台儀錶板
            Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);
            
            // 後台產品管理
            Route::get('/admin/products', [\App\Http\Controllers\AdminController::class, 'adminProducts']);
            Route::get('/admin/products/export', [\App\Http\Controllers\AdminController::class, 'exportProducts']);
            Route::get('/admin/products/{id}', [\App\Http\Controllers\AdminController::class, 'adminProductShow']);
            Route::post('/admin/products', [\App\Http\Controllers\AdminController::class, 'adminProductStore']);
            Route::put('/admin/products/{id}', [\App\Http\Controllers\AdminController::class, 'adminProductUpdate']);
            Route::delete('/admin/products/{id}', [\App\Http\Controllers\AdminController::class, 'adminProductDestroy']);
            // 新增刪除商品額外圖片 API
            Route::delete('/admin/products/{id}/image', [\App\Http\Controllers\AdminController::class, 'deleteProductImage']);
            // 新增圖片上傳 API
            Route::post('/admin/upload-image', [\App\Http\Controllers\AdminController::class, 'uploadImage']);
            
            // 後台優惠券管理
            Route::get('/admin/coupons', [\App\Http\Controllers\AdminController::class, 'adminCoupons']);
            Route::get('/admin/coupons/export', [\App\Http\Controllers\AdminController::class, 'exportCoupons']);
            Route::get('/admin/coupons/{id}', [\App\Http\Controllers\AdminController::class, 'adminCouponShow']);
            Route::post('/admin/coupons', [\App\Http\Controllers\AdminController::class, 'adminCouponStore']);
            Route::put('/admin/coupons/{id}', [\App\Http\Controllers\AdminController::class, 'adminCouponUpdate']);
            Route::delete('/admin/coupons/{id}', [\App\Http\Controllers\AdminController::class, 'adminCouponDestroy']);
            
            // 後台門市管理
            Route::get('/admin/stores', [\App\Http\Controllers\AdminStoreController::class, 'index']);
            Route::get('/admin/stores/{id}', [\App\Http\Controllers\AdminStoreController::class, 'show']);
            Route::post('/admin/stores', [\App\Http\Controllers\AdminStoreController::class, 'store']);
            Route::put('/admin/stores/{id}', [\App\Http\Controllers\AdminStoreController::class, 'update']);
            Route::delete('/admin/stores/{id}', [\App\Http\Controllers\AdminStoreController::class, 'destroy']);
        });
    });

    // 其他公開路由
    Route::get('/faqs', [\App\Http\Controllers\FAQController::class, 'index']);
    Route::get('/stores', [\App\Http\Controllers\StoreController::class, 'index']);
    Route::post('/gift-service', [\App\Http\Controllers\GiftServiceController::class, 'store']);
    Route::get('/food-trace', [\App\Http\Controllers\FoodTraceController::class, 'show']);
    Route::post('/group-orders', [\App\Http\Controllers\GroupOrderController::class, 'store']);
    Route::get('/recommend', [\App\Http\Controllers\RecommendController::class, 'index']);
}); 

// Admin 庫存管理
Route::middleware(['auth:sanctum'])->prefix('v1/admin')->group(function () {
    Route::get('inventories', [\App\Http\Controllers\AdminInventoryController::class, 'adminInventories']);
    Route::post('inventories/{id}/adjust', [\App\Http\Controllers\AdminInventoryController::class, 'adminInventoryAdjust']);
}); 