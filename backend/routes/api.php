<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;

// 測試路由
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
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
    Route::post('/auth/line-login', [AuthController::class, 'lineLogin']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

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
    
        // 優惠券相關
    Route::get('/coupons', [CouponController::class, 'index']);
        Route::post('/coupons/redeem', [CouponController::class, 'redeem']);
    
        // 點數相關
        Route::get('/points', [\App\Http\Controllers\PointController::class, 'index']);
        Route::post('/points/earn', [\App\Http\Controllers\PointController::class, 'earn']);
        Route::post('/points/spend', [\App\Http\Controllers\PointController::class, 'spend']);
    });

    // 其他公開路由
    Route::get('/faqs', [\App\Http\Controllers\FAQController::class, 'index']);
    Route::get('/stores', [\App\Http\Controllers\StoreController::class, 'index']);
    Route::post('/gift-service', [\App\Http\Controllers\GiftServiceController::class, 'store']);
    Route::get('/food-trace', [\App\Http\Controllers\FoodTraceController::class, 'show']);
    Route::post('/group-orders', [\App\Http\Controllers\GroupOrderController::class, 'store']);
    Route::get('/recommend', [\App\Http\Controllers\RecommendController::class, 'index']);
}); 