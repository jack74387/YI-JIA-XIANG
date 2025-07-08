<?php
namespace App\Http\Controllers;

class RecommendController extends Controller
{
    public function index()
    {
        $products = [
            ['id' => 1, 'name' => '蜜汁原味豬肉乾', 'image' => '/images/products/honey-jerky.jpg'],
            ['id' => 2, 'name' => '黑胡椒豬肉乾', 'image' => '/images/products/black-pepper-jerky.jpg'],
            ['id' => 3, 'name' => '杏仁厚片豬肉乾', 'image' => '/images/products/almond-thick-jerky.jpg'],
            ['id' => 4, 'name' => '旗魚鬆', 'image' => '/images/products/sailfish-floss.jpg'],
        ];
        return response()->json(['success' => true, 'products' => $products]);
    }
} 