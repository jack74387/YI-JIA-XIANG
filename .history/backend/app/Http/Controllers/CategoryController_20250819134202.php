<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    private $internalApiUrl = 'https://192.168.99.27';

    public function index()
    {
        try {
            // 檢查快取是否存在
            $cachedCategories = Cache::get('categories');
            if ($cachedCategories) {
                return response()->json($cachedCategories, 200);
            }

            // 呼叫內網 API
            $response = Http::withoutVerifying() // 忽略 SSL 驗證
                          ->timeout(10)
                          ->get($this->internalApiUrl . '/api/v1/categories');

            if ($response->successful()) {
                $categories = $response->json();

                // 將結果存入快取，保存 10 分鐘
                Cache::put('categories', $categories, now()->addMinutes(10));

                return response()->json($categories, 200);
            } else {
                // 如果內網 API 失敗，嘗試回傳快取的資料
                $cachedCategories = Cache::get('categories');
                if ($cachedCategories) {
                    return response()->json($cachedCategories, 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch categories',
                    'status' => $response->status(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            // 如果內網 API 掛掉，回傳快取的資料（如果有）
            $cachedCategories = Cache::get('categories');
            if ($cachedCategories) {
                return response()->json($cachedCategories, 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // 檢查快取是否存在
            $cacheKey = "category_{$id}";
            $cachedCategory = Cache::get($cacheKey);
            if ($cachedCategory) {
                return response()->json($cachedCategory, 200);
            }

            // 呼叫內網 API
            $response = Http::withoutVerifying()
                          ->timeout(10)
                          ->get($this->internalApiUrl . "/api/v1/categories/{$id}");

            if ($response->successful()) {
                $category = $response->json();

                // 將結果存入快取，保存 10 分鐘
                Cache::put($cacheKey, $category, now()->addMinutes(10));

                return response()->json($category, 200);
            } else {
                // 如果內網 API 失敗，嘗試回傳快取的資料
                $cachedCategory = Cache::get($cacheKey);
                if ($cachedCategory) {
                    return response()->json($cachedCategory, 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch category',
                    'status' => $response->status(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            // 如果內網 API 掛掉，回傳快取的資料（如果有）
            $cacheKey = "category_{$id}";
            $cachedCategory = Cache::get($cacheKey);
            if ($cachedCategory) {
                return response()->json($cachedCategory, 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }
} 