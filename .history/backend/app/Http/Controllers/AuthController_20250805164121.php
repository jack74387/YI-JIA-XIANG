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
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'birthday' => 'required|date|before:today',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => '請輸入姓名',
            'name.max' => '姓名不能超過50個字元',
            'phone.required' => '請輸入手機號碼',
            'phone.unique' => '此手機號碼已被註冊',
            'email.required' => '請輸入電子信箱',
            'email.email' => '請輸入有效的電子信箱格式',
            'email.unique' => '此電子信箱已被註冊',
            'birthday.required' => '請選擇出生日期',
            'birthday.date' => '請輸入有效的日期格式',
            'birthday.before' => '出生日期不能是今天或未來日期',
            'password.required' => '請輸入密碼',
            'password.min' => '密碼至少需要6個字元',
            'password.confirmed' => '密碼確認不符',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '註冊資料驗證失敗',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'birthday' => $request->birthday,
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
            'login' => 'required|string', // 可以是手機號碼或電子信箱
            'password' => 'required|string',
        ], [
            'login.required' => '請輸入手機號碼或電子信箱',
            'password.required' => '請輸入密碼',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請提供有效的登入資訊',
                'errors' => $validator->errors()
            ], 422);
        }

        // 判斷是手機號碼還是電子信箱
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        // 先檢查用戶是否存在
        $user = User::where($loginField, $request->login)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '無此帳號，請檢查帳號是否正確'
            ], 401);
        }
        
        // 檢查密碼是否正確
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => '密碼錯誤，請重新輸入'
            ], 401);
        }

        // 登入成功
        Auth::login($user);
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

    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請提供有效的 Google 登入憑證',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 這裡應該實作 Google OAuth 驗證邏輯
            // 1. 驗證 Google token
            // 2. 取得用戶資訊
            // 3. 檢查用戶是否已存在，不存在則創建
            // 4. 登入用戶並回傳 token
            
            // 目前僅回傳成功訊息作為範例
            return response()->json([
                'success' => true,
                'message' => 'Google 登入成功'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google 登入失敗，請稍後再試'
            ], 500);
        }
    }

    public function facebookLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facebook_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '請提供有效的 Facebook 登入憑證',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 這裡應該實作 Facebook Login 驗證邏輯
            // 1. 驗證 Facebook token
            // 2. 取得用戶資訊
            // 3. 檢查用戶是否已存在，不存在則創建
            // 4. 登入用戶並回傳 token
            
            // 目前僅回傳成功訊息作為範例
            return response()->json([
                'success' => true,
                'message' => 'Facebook 登入成功'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Facebook 登入失敗，請稍後再試'
            ], 500);
        }
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
            $email = $request->email;
            
            // 產生重設 token
            $token = Str::random(64);
            
            // 刪除舊的 token
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            
            // 儲存新的 token
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);
            
            // 產生重設 URL
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($email);
            
            // 發送重設密碼郵件
            Mail::html(view('emails.reset-password', [
                'resetUrl' => $resetUrl,
                'email' => $email
            ])->render(), function ($message) use ($email) {
                $message->to($email)
                       ->subject('【一佳香肉脯行】密碼重設請求');
            });
            
            return response()->json([
                'success' => true,
                'message' => '重設密碼連結已發送到您的電子郵件，請檢查您的信箱（包含垃圾郵件夾）'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Password reset failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            
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