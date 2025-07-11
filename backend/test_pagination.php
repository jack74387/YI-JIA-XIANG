<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// 測試分頁功能
echo "=== 測試分頁功能 ===\n";

// 獲取產品總數
$total = DB::table('products')->count();
echo "產品總數: {$total}\n";

// 測試每頁10筆的分頁
$perPage = 10;
$totalPages = ceil($total / $perPage);

echo "每頁顯示: {$perPage} 筆\n";
echo "總頁數: {$totalPages}\n";

// 測試第一頁
$page1 = DB::table('products')
    ->select('id', 'name', 'status')
    ->orderBy('id')
    ->limit($perPage)
    ->offset(0)
    ->get();

echo "\n第一頁產品 (前5筆):\n";
foreach ($page1->take(5) as $product) {
    echo "- ID: {$product->id}, 名稱: {$product->name}, 狀態: {$product->status}\n";
}

// 測試第二頁
if ($totalPages > 1) {
    $page2 = DB::table('products')
        ->select('id', 'name', 'status')
        ->orderBy('id')
        ->limit($perPage)
        ->offset($perPage)
        ->get();

    echo "\n第二頁產品 (前5筆):\n";
    foreach ($page2->take(5) as $product) {
        echo "- ID: {$product->id}, 名稱: {$product->name}, 狀態: {$product->status}\n";
    }
}

// 測試搜尋分頁
echo "\n=== 測試搜尋分頁 ===\n";
$searchResults = DB::table('products')
    ->where('name', 'like', '%產品%')
    ->count();

echo "包含'產品'的產品數量: {$searchResults}\n";

if ($searchResults > 0) {
    $searchPage1 = DB::table('products')
        ->where('name', 'like', '%產品%')
        ->select('id', 'name', 'status')
        ->orderBy('id')
        ->limit($perPage)
        ->offset(0)
        ->get();

    echo "搜尋結果第一頁:\n";
    foreach ($searchPage1 as $product) {
        echo "- ID: {$product->id}, 名稱: {$product->name}, 狀態: {$product->status}\n";
    }
}

// 測試狀態篩選分頁
echo "\n=== 測試狀態篩選分頁 ===\n";
$publishedCount = DB::table('products')
    ->where('status', 'published')
    ->count();

echo "已上架產品數量: {$publishedCount}\n";

if ($publishedCount > 0) {
    $publishedPage1 = DB::table('products')
        ->where('status', 'published')
        ->select('id', 'name', 'status')
        ->orderBy('id')
        ->limit($perPage)
        ->offset($perPage)
        ->get();

    echo "已上架產品第一頁:\n";
    foreach ($publishedPage1 as $product) {
        echo "- ID: {$product->id}, 名稱: {$product->name}, 狀態: {$product->status}\n";
    }
}

echo "\n=== 分頁測試完成 ===\n"; 