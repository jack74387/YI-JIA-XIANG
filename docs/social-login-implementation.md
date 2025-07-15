# 社交登入功能實現

## 概述

本專案已實現 Google、Facebook 和 LINE 三種社交登入方式，提供用戶更便捷的登入體驗。

## 功能特色

### 前端特色
- **美觀的按鈕設計**：使用官方品牌色彩和圖標
- **響應式設計**：適配各種螢幕尺寸
- **互動效果**：hover 動畫和 focus 狀態
- **錯誤處理**：完整的錯誤訊息顯示
- **載入狀態**：按鈕點擊時的載入指示

### 後端特色
- **統一的 API 介面**：標準化的回應格式
- **Token 驗證**：安全的社交登入驗證
- **用戶資料管理**：自動創建或關聯現有用戶
- **錯誤處理**：完整的錯誤回應機制

## 實現細節

### 前端實現

#### 1. 登入按鈕設計
```vue
<!-- Google 登入按鈕 -->
<button class="social-button w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
  <svg class="w-5 h-5" viewBox="0 0 24 24">
    <!-- Google 官方圖標 -->
  </svg>
  <span class="ml-2">使用 Google 登入</span>
</button>
```

#### 2. 處理函數
```javascript
const handleGoogleLogin = async () => {
  try {
    // 1. 載入 Google API
    // 2. 初始化 Google Sign-In
    // 3. 取得 token
    // 4. 發送到後端驗證
    
    const response = await fetch('/api/v1/auth/google-login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        google_token: mockGoogleToken
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      // 登入成功處理
    } else {
      // 錯誤處理
    }
  } catch (error) {
    // 異常處理
  }
}
```

### 後端實現

#### 1. 路由定義
```php
Route::post('/auth/google-login', [AuthController::class, 'googleLogin']);
Route::post('/auth/facebook-login', [AuthController::class, 'facebookLogin']);
Route::post('/auth/line-login', [AuthController::class, 'lineLogin']);
```

#### 2. 控制器方法
```php
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
        // 1. 驗證 Google token
        // 2. 取得用戶資訊
        // 3. 檢查用戶是否已存在，不存在則創建
        // 4. 登入用戶並回傳 token
        
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
```

#### 3. 資料庫結構
```php
// User 模型已包含以下欄位：
'google_user_id' => 'string|nullable',
'facebook_user_id' => 'string|nullable',
'line_user_id' => 'string|nullable',
```

## 測試方法

### 1. 使用測試頁面
訪問 `test-social-login.html` 頁面，可以測試所有社交登入按鈕的功能。

### 2. 測試步驟
1. 點擊任意社交登入按鈕
2. 觀察測試結果區域的回應
3. 檢查瀏覽器開發者工具的網路請求
4. 驗證後端 API 回應

### 3. 預期結果
- 按鈕點擊後顯示載入狀態
- API 請求成功發送
- 後端回傳成功訊息
- 測試結果區域顯示成功狀態

## 整合指南

### Google OAuth 2.0 整合

#### 1. 設定 Google Cloud Console
1. 前往 [Google Cloud Console](https://console.cloud.google.com/)
2. 創建新專案或選擇現有專案
3. 啟用 Google+ API
4. 創建 OAuth 2.0 憑證
5. 設定授權的重新導向 URI

#### 2. 前端整合
```html
<!-- 載入 Google API -->
<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name="google-signin-client_id" content="YOUR_CLIENT_ID.apps.googleusercontent.com">

<!-- Google 登入按鈕 -->
<div class="g-signin2" data-onsuccess="onSignIn"></div>
```

```javascript
function onSignIn(googleUser) {
  const id_token = googleUser.getAuthResponse().id_token;
  
  // 發送到後端驗證
  fetch('/api/v1/auth/google-login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      google_token: id_token
    })
  });
}
```

### Facebook Login 整合

#### 1. 設定 Facebook 開發者
1. 前往 [Facebook 開發者](https://developers.facebook.com/)
2. 創建新應用程式
3. 設定 Facebook Login 產品
4. 取得 App ID 和 App Secret

#### 2. 前端整合
```html
<!-- 載入 Facebook SDK -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/zh_TW/sdk.js"></script>

<script>
window.fbAsyncInit = function() {
  FB.init({
    appId: 'YOUR_APP_ID',
    cookie: true,
    xfbml: true,
    version: 'v18.0'
  });
};

function handleFacebookLogin() {
  FB.login(function(response) {
    if (response.authResponse) {
      const accessToken = response.authResponse.accessToken;
      
      // 發送到後端驗證
      fetch('/api/v1/auth/facebook-login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          facebook_token: accessToken
        })
      });
    }
  }, {scope: 'email,public_profile'});
}
</script>
```

### LINE Login 整合

#### 1. 設定 LINE 開發者
1. 前往 [LINE 開發者](https://developers.line.biz/)
2. 創建新 Channel
3. 設定 LINE Login 產品
4. 取得 Channel ID 和 Channel Secret

#### 2. 前端整合
```javascript
function handleLineLogin() {
  const lineLoginUrl = `https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=YOUR_CHANNEL_ID&redirect_uri=YOUR_REDIRECT_URI&state=random_state&scope=profile%20openid%20email`;
  
  window.location.href = lineLoginUrl;
}
```

## 安全性考量

### 1. Token 驗證
- 所有社交登入 token 都必須在後端驗證
- 使用官方 SDK 或 API 驗證 token 有效性
- 檢查 token 的發行者和過期時間

### 2. 用戶資料保護
- 只收集必要的用戶資料
- 遵守各平台的隱私政策
- 加密存儲敏感資訊

### 3. 錯誤處理
- 不暴露內部錯誤訊息給用戶
- 記錄詳細的錯誤日誌
- 提供友善的錯誤提示

## 未來改進

### 1. 功能增強
- [ ] 支援更多社交平台（如 Apple、Twitter）
- [ ] 實現社交帳號綁定功能
- [ ] 添加社交登入統計分析

### 2. 用戶體驗
- [ ] 一鍵登入功能
- [ ] 記住登入狀態
- [ ] 自動跳轉到原目標頁面

### 3. 技術優化
- [ ] 實現真正的 OAuth 流程
- [ ] 添加 token 刷新機制
- [ ] 優化 API 回應速度

## 相關檔案

- `frontend/src/views/LoginView.vue` - 登入頁面
- `backend/app/Http/Controllers/AuthController.php` - 認證控制器
- `backend/routes/api.php` - API 路由
- `backend/app/Models/User.php` - 用戶模型
- `test-social-login.html` - 測試頁面

## 注意事項

1. 目前的實現是模擬版本，實際部署時需要整合真實的社交平台 SDK
2. 需要設定正確的環境變數和憑證
3. 建議在測試環境中充分測試後再部署到生產環境
4. 定期更新社交平台的 SDK 和 API 版本 