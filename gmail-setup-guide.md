## 📧 Gmail SMTP 設定完整指南

### � 目前狀態
- ✅ 聯絡表單功能已完成
- ✅ 目前使用 log 模式（郵件記錄在日誌中）
- ⚠️ 需要設定 Gmail SMTP 才能真正發送郵件

### 📋 設定步驟

#### 第一步：啟用 Gmail 兩步驟驗證
1. 前往：https://myaccount.google.com/security
2. 在「登入 Google」區域點選「兩步驟驗證」
3. 按照指示完成設定

#### 第二步：產生應用程式密碼
1. 在安全性頁面中，找到「應用程式密碼」
2. 點選「應用程式密碼」
3. 選擇應用程式：「郵件」
4. 選擇裝置：「其他（自訂名稱）」
5. 輸入：「一佳香肉脯行網站」
6. 點選「產生」
7. **複製顯示的 16 位數密碼**（格式：`abcd efgh ijkl mnop`）

#### 第三步：更新設定檔
編輯 `backend/.env` 文件，更新以下設定：

```bash
# 將 log 改為 smtp
MAIL_MAILER=smtp

# 貼上您的應用程式密碼（移除空格）
MAIL_PASSWORD=您的16位數應用程式密碼
```

#### 第四步：清除快取並測試
```bash
cd backend
php artisan config:clear
php test-mail-config.php
```

### � 目前可以測試的功能

即使沒有設定 Gmail SMTP，您仍然可以測試聯絡表單：

1. **前端測試**：http://localhost:5173/contact
2. **檢查日誌**：郵件內容會記錄在 `backend/storage/logs/laravel.log`
3. **查看郵件內容**：
   ```powershell
   Get-Content backend/storage/logs/laravel.log -Tail 50
   ```

### 📧 真實發送郵件

完成 Gmail 設定後：
- ✅ 業主會收到通知郵件：yijiaxiang88@gmail.com
- ✅ 客戶會收到確認郵件
- ✅ 包含完整的門市聯絡資訊

### � 重要提醒

1. **應用程式密碼 ≠ Gmail 登入密碼**
2. **必須先啟用兩步驟驗證**
3. **應用程式密碼只顯示一次，請立即記錄**
4. **在 .env 中使用時請移除空格**

例如：
- Google 顯示：`abcd efgh ijkl mnop`
- .env 設定：`MAIL_PASSWORD=abcdefghijklmnop`
