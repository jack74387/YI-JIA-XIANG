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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => '註冊成功',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '註冊失敗，請稍後再試'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請提供有效的電子郵件和密碼',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => '帳號或密碼錯誤'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => '登入成功',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'success' => true,
                'message' => '登出成功'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '登出失敗'
            ], 500);
        }
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function lineLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'line_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請提供有效的 LINE 登入憑證',
                'errors' => $validator->errors()
            ], 422);
        }

        // 這裡應該實作 LINE 登入驗證邏輯
        // 目前僅回傳成功訊息作為範例
        return response()->json([
            'success' => true,
            'message' => 'LINE 登入成功'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請提供有效的電子郵件地址',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 這裡應該實作密碼重設邏輯
            // 目前僅回傳成功訊息作為範例
            return response()->json([
                'success' => true,
                'message' => '已寄送重設密碼連結到您的電子郵件'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '發送失敗，請稍後再試'
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請提供有效的重設資訊',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 這裡應該實作密碼重設邏輯
            // 目前僅回傳成功訊息作為範例
            return response()->json([
                'success' => true,
                'message' => '密碼重設成功'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '密碼重設失敗，請稍後再試'
            ], 500);
        }
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->where('is_admin', true)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return response()->json(['success' => false, 'message' => '帳號或密碼錯誤'], 401);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * 管理員修改密碼
     */
    public function changeAdminPassword(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['success' => false, 'message' => '無權限'], 403);
        }
        $validator = \Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }
        if (!\Hash::check($request->old_password, $user->password)) {
            return response()->json(['success' => false, 'message' => '舊密碼錯誤'], 400);
        }
        $user->password = \Hash::make($request->password);
        $user->save();
        return response()->json(['success' => true, 'message' => '密碼修改成功']);
    }
} 