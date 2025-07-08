<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// 測試 API 路由
Route::get('/api/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// 產品相關路由
Route::get('/api/v1/products', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('/api/v1/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);

// 分類相關路由
Route::get('/api/v1/categories', [App\Http\Controllers\CategoryController::class, 'index']);
Route::get('/api/v1/categories/{id}', [App\Http\Controllers\CategoryController::class, 'show']);

// 訂單相關路由
Route::get('/api/v1/orders', [App\Http\Controllers\OrderController::class, 'index']);
Route::get('/api/v1/orders/{id}', [App\Http\Controllers\OrderController::class, 'show']);
Route::post('/api/v1/orders', [App\Http\Controllers\OrderController::class, 'store']);

// 優惠券相關路由
Route::get('/api/v1/coupons', [App\Http\Controllers\CouponController::class, 'index']);
Route::get('/api/v1/coupons/{id}', [App\Http\Controllers\CouponController::class, 'show']);

// 庫存相關路由
Route::get('/api/v1/inventories', [App\Http\Controllers\InventoryController::class, 'index']);
Route::get('/api/v1/inventories/{id}', [App\Http\Controllers\InventoryController::class, 'show']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', function(\Illuminate\Http\Request $request) {
    $controller = app(AuthController::class);
    $response = $controller->login($request);
    // 登入成功自動導向首頁
    $data = $response->getData(true);
    if (!empty($data['success'])) {
        return redirect('/'); // 或 return redirect('/member-center');
    }
    return $response;
});
Route::post('/auth/line-login', [AuthController::class, 'lineLogin']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
