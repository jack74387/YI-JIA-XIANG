<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return response()->json(['success' => true, 'user' => $user]);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt($data)) {
            return response()->json(['success' => false, 'message' => '帳號或密碼錯誤'], 401);
        }
        $user = Auth::user();
        return response()->json(['success' => true, 'user' => $user]);
    }
    public function lineLogin(Request $request)
    {
        // 假設已驗證 LINE token，這裡僅回傳成功
        return response()->json(['success' => true, 'message' => 'LINE 登入成功']);
    }
    public function forgotPassword(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        // 實際應寄送重設信件，這裡僅回傳成功
        return response()->json(['success' => true, 'message' => '已寄送重設密碼連結']);
    }
} 