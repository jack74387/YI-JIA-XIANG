<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ProductProxyController extends Controller
{
    private $internalApiUrl = 'https://192.168.99.27';

    public function index(Request $request)
    {
        try {
            // 生成快取 key，包含查詢參數
            $queryParams = $request->query();
            $cacheKey = 'products_' . md5(serialize($queryParams));
            
            // 檢查快取是否存在
            $cachedProducts = Cache::get($cacheKey);
            if ($cachedProducts) {
                return response()->json($cachedProducts, 200);
            }

            // 呼叫內網 API，傳遞所有查詢參數
            $response = Http::withoutVerifying()
                          ->timeout(15)
                          ->get($this->internalApiUrl . '/api/v1/products', $queryParams);

            if ($response->successful()) {
                $products = $response->json();

                // 將結果存入快取，保存 5 分鐘
                Cache::put($cacheKey, $products, now()->addMinutes(5));

                return response()->json($products, 200);
            } else {
                // 如果內網 API 失敗，嘗試回傳快取的資料
                $cachedProducts = Cache::get($cacheKey);
                if ($cachedProducts) {
                    return response()->json($cachedProducts, 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch products',
                    'status' => $response->status(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            // 如果內網 API 掛掉，回傳快取的資料（如果有）
            $cacheKey = 'products_' . md5(serialize($request->query()));
            $cachedProducts = Cache::get($cacheKey);
            if ($cachedProducts) {
                return response()->json($cachedProducts, 200);
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
            $cacheKey = "product_{$id}";
            $cachedProduct = Cache::get($cacheKey);
            if ($cachedProduct) {
                return response()->json($cachedProduct, 200);
            }

            // 呼叫內網 API
            $response = Http::withoutVerifying()
                          ->timeout(10)
                          ->get($this->internalApiUrl . "/api/v1/products/{$id}");

            if ($response->successful()) {
                $product = $response->json();

                // 將結果存入快取，保存 10 分鐘
                Cache::put($cacheKey, $product, now()->addMinutes(10));

                return response()->json($product, 200);
            } else {
                // 如果內網 API 失敗，嘗試回傳快取的資料
                $cachedProduct = Cache::get($cacheKey);
                if ($cachedProduct) {
                    return response()->json($cachedProduct, 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch product',
                    'status' => $response->status(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            // 如果內網 API 掛掉，回傳快取的資料（如果有）
            $cacheKey = "product_{$id}";
            $cachedProduct = Cache::get($cacheKey);
            if ($cachedProduct) {
                return response()->json($cachedProduct, 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRecommendations($id)
    {
        try {
            // 檢查快取是否存在
            $cacheKey = "product_recommendations_{$id}";
            $cachedRecommendations = Cache::get($cacheKey);
            if ($cachedRecommendations) {
                return response()->json($cachedRecommendations, 200);
            }

            // 呼叫內網 API
            $response = Http::withoutVerifying()
                          ->timeout(10)
                          ->get($this->internalApiUrl . "/api/v1/products/{$id}/recommendations");

            if ($response->successful()) {
                $recommendations = $response->json();

                // 將結果存入快取，保存 15 分鐘
                Cache::put($cacheKey, $recommendations, now()->addMinutes(15));

                return response()->json($recommendations, 200);
            } else {
                // 如果內網 API 失敗，嘗試回傳快取的資料
                $cachedRecommendations = Cache::get($cacheKey);
                if ($cachedRecommendations) {
                    return response()->json($cachedRecommendations, 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch recommendations',
                    'status' => $response->status(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            // 如果內網 API 掛掉，回傳快取的資料（如果有）
            $cacheKey = "product_recommendations_{$id}";
            $cachedRecommendations = Cache::get($cacheKey);
            if ($cachedRecommendations) {
                return response()->json($cachedRecommendations, 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }
}
