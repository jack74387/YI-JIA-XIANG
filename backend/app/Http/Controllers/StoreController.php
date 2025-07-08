<?php
namespace App\Http\Controllers;

class StoreController extends Controller
{
    public function index()
    {
        $stores = [
            ['id' => 1, 'name' => '台東總店', 'address' => '台東市中華路一段123號', 'phone' => '089-123456', 'map' => 'https://goo.gl/maps/xxxx'],
            ['id' => 2, 'name' => '台北分店', 'address' => '台北市信義區松仁路456號', 'phone' => '02-23456789', 'map' => 'https://goo.gl/maps/yyyy'],
        ];
        return response()->json(['success' => true, 'stores' => $stores]);
    }
} 