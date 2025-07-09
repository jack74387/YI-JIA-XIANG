# 一佳香 API 認證功能測試指南

## 概述

本文檔說明如何測試一佳香網站的認證 API 功能，包括註冊、登入、登出等功能。

## 測試環境

- 後端 API: `http://127.0.0.1:8000/api/v1`
- 前端: `http://localhost:5173`

## API 端點

### 1. 用戶註冊

**端點:** `POST /api/v1/auth/register`

**請求範例:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "測試用戶",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**成功回應:**
```json
{
  "success": true,
  "message": "註冊成功",
  "user": {
    "id": 1,
    "name": "測試用戶",
    "email": "test@example.com",
    "email_verified_at": null,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  },
  "token": "1|abcdef123456..."
}
```

### 2. 用戶登入

**端點:** `POST /api/v1/auth/login`

**請求範例:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**成功回應:**
```json
{
  "success": true,
  "message": "登入成功",
  "user": {
    "id": 1,
    "name": "測試用戶",
    "email": "test@example.com",
    "email_verified_at": null,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  },
  "token": "1|abcdef123456..."
}
```

### 3. 獲取當前用戶資訊

**端點:** `GET /api/v1/auth/user`

**請求範例:**
```bash
curl -X GET http://127.0.0.1:8000/api/v1/auth/user \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Accept: application/json"
```

**成功回應:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "測試用戶",
    "email": "test@example.com",
    "email_verified_at": null,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

### 4. 用戶登出

**端點:** `POST /api/v1/auth/logout`

**請求範例:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/logout \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Accept: application/json"
```

**成功回應:**
```json
{
  "success": true,
  "message": "登出成功"
}
```

### 5. 忘記密碼

**端點:** `POST /api/v1/auth/forgot-password`

**請求範例:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/forgot-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com"
  }'
```

**成功回應:**
```json
{
  "success": true,
  "message": "已寄送重設密碼連結到您的電子郵件"
}
```

## 測試用戶

系統預設創建了以下測試用戶：

1. **測試用戶**
   - Email: `test@example.com`
   - 密碼: `password123`

2. **管理員**
   - Email: `admin@yijiaxiang.com`
   - 密碼: `admin123`

## 前端測試

### 1. 啟動前端開發伺服器

```bash
cd frontend
npm run dev
```

### 2. 測試登入流程

1. 訪問 `http://localhost:5173/login`
2. 使用測試用戶憑證登入
3. 檢查是否成功跳轉到首頁
4. 檢查導航欄是否顯示用戶資訊

### 3. 測試註冊流程

1. 訪問 `http://localhost:5173/register`
2. 填寫註冊表單
3. 提交後檢查是否自動登入
4. 檢查是否跳轉到首頁

### 4. 測試路由保護

1. 登入後嘗試訪問 `/login` 或 `/register`
2. 應該自動跳轉到首頁
3. 登出後嘗試訪問 `/profile`
4. 應該自動跳轉到登入頁面

## 錯誤處理測試

### 1. 無效的登入憑證

**請求:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "wrong@example.com",
    "password": "wrongpassword"
  }'
```

**回應:**
```json
{
  "success": false,
  "message": "帳號或密碼錯誤"
}
```

### 2. 無效的註冊資料

**請求:**
```bash
curl -X POST http://127.0.0.1:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "",
    "email": "invalid-email",
    "password": "123",
    "password_confirmation": "456"
  }'
```

**回應:**
```json
{
  "success": false,
  "message": "驗證失敗",
  "errors": {
    "name": ["The name field is required."],
    "email": ["The email field must be a valid email address."],
    "password": ["The password field must be at least 6 characters."],
    "password_confirmation": ["The password confirmation field does not match password."]
  }
}
```

## 安全測試

### 1. 未授權訪問

嘗試訪問需要認證的端點而不提供 token：

```bash
curl -X GET http://127.0.0.1:8000/api/v1/auth/user \
  -H "Accept: application/json"
```

應該返回 401 未授權錯誤。

### 2. 無效的 Token

使用無效的 token 訪問受保護的端點：

```bash
curl -X GET http://127.0.0.1:8000/api/v1/auth/user \
  -H "Authorization: Bearer invalid-token" \
  -H "Accept: application/json"
```

應該返回 401 未授權錯誤。

## 資料庫測試

### 1. 檢查用戶表

```sql
SELECT id, name, email, created_at FROM users;
```

### 2. 檢查 Sanctum tokens 表

```sql
SELECT id, tokenable_type, tokenable_id, name, created_at FROM personal_access_tokens;
```

## 常見問題

### 1. CORS 錯誤

如果遇到 CORS 錯誤，確保後端已正確配置 CORS 設定。

### 2. Token 過期

Sanctum tokens 預設不會過期，但可以設定過期時間。

### 3. 密碼雜湊

確保密碼使用 Laravel 的 Hash facade 進行雜湊處理。

## 下一步

1. 實作 LINE 登入整合
2. 添加電子郵件驗證功能
3. 實作密碼重設功能
4. 添加雙因素認證
5. 實作角色和權限管理 