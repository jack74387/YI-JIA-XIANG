<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return response()->json(Inventory::with('product')->get());
    }

    public function show($id)
    {
        return response()->json(Inventory::with('product')->findOrFail($id));
    }
} 