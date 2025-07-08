<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class GroupOrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'desc' => 'required|string',
        ]);
        // 實際應儲存團購需求
        return response()->json(['success' => true, 'message' => '已收到團購需求', 'data' => $data]);
    }
} 