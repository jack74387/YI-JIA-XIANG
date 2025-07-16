<?php
namespace App\Http\Controllers;

use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::active()->get();
        return response()->json(['success' => true, 'stores' => $stores]);
    }
} 