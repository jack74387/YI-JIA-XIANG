<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\InventoryController;

// 測試路由
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::prefix('v1')->group(function () {
    // 產品相關路由
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    
    // 分類相關路由
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    
    // 訂單相關路由
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    
    // 優惠券相關路由
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::get('/coupons/{id}', [CouponController::class, 'show']);
    
    // 庫存相關路由
    Route::get('/inventories', [InventoryController::class, 'index']);
    Route::get('/inventories/{id}', [InventoryController::class, 'show']);

    // 註冊禮盒加值服務 API 路由 POST /api/v1/gift-service 對應 GiftServiceController@store。
    Route::post('/gift-service', [\App\Http\Controllers\GiftServiceController::class, 'store']);
    Route::get('/food-trace', [\App\Http\Controllers\FoodTraceController::class, 'show']);

    Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/auth/line-login', [\App\Http\Controllers\AuthController::class, 'lineLogin']);
    Route::post('/auth/forgot-password', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);

    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index']);
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add']);
    Route::put('/cart/update', [\App\Http\Controllers\CartController::class, 'update']);
    Route::delete('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove']);

    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);
    Route::get('/orders/{id}', [\App\Http\Controllers\OrderController::class, 'show']);
    Route::get('/user/orders', [\App\Http\Controllers\OrderController::class, 'userOrders']);
    Route::put('/orders/{id}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus']);

    Route::get('/faqs', [\App\Http\Controllers\FAQController::class, 'index']);
    Route::get('/stores', [\App\Http\Controllers\StoreController::class, 'index']);

    Route::get('/coupons', [\App\Http\Controllers\CouponController::class, 'index']);
    Route::post('/coupons/redeem', [\App\Http\Controllers\CouponController::class, 'redeem']);

    Route::get('/points', [\App\Http\Controllers\PointController::class, 'index']);
    Route::post('/points/earn', [\App\Http\Controllers\PointController::class, 'earn']);
    Route::post('/points/spend', [\App\Http\Controllers\PointController::class, 'spend']);

    Route::post('/group-orders', [\App\Http\Controllers\GroupOrderController::class, 'store']);
    Route::get('/recommend', [\App\Http\Controllers\RecommendController::class, 'index']);
}); 