## Gmail SMTP 設定指南

### 🔧 設定步驟

#### 1. 啟用 Gmail 兩步驟驗證
1. 登入您的 Google 帳戶：https://myaccount.google.com/
2. 點選「安全性」
3. 在「登入 Google」區域，啟用「兩步驟驗證」

#### 2. 產生應用程式密碼
1. 在「安全性」頁面中，找到「應用程式密碼」
2. 選擇「郵件」應用程式
3. 選擇「其他」裝置，輸入「一佳香肉脯行網站」
4. 複製產生的 16 位數密碼

#### 3. 更新 .env 設定
將產生的應用程式密碼貼到 backend/.env 文件中：

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yijiaxiang88@gmail.com
MAIL_PASSWORD=你的16位數應用程式密碼
MAIL_FROM_ADDRESS="yijiaxiang88@gmail.com"
MAIL_FROM_NAME="一佳香肉脯行"
MAIL_ENCRYPTION=tls
```

⚠️ **重要提醒**：
- 應用程式密碼不是您的 Gmail 登入密碼
- 應用程式密碼格式通常是：`abcd efgh ijkl mnop`（16位數，有空格）
- 在 .env 中使用時請移除空格：`abcdefghijklmnop`

#### 4. 清除快取並測試
```bash
cd backend
php artisan config:clear
php artisan cache:clear
```

### 📧 測試步驟

1. 完成上述設定後
2. 前往網站聯絡頁面：http://localhost:5173/contact
3. 填寫測試表單並送出
4. 檢查 yijiaxiang88@gmail.com 信箱（包含垃圾郵件夾）

### 🔍 故障排除

如果仍然沒有收到郵件，請檢查：

1. **檢查日誌錯誤**：
   ```bash
   Get-Content backend/storage/logs/laravel.log -Tail 20
   ```

2. **常見錯誤**：
   - 應用程式密碼錯誤
   - 兩步驟驗證未啟用
   - Gmail 安全性設定問題

3. **Gmail 安全性檢查**：
   - 檢查 Gmail 的「安全性」設定
   - 確認沒有封鎖 SMTP 連線

### 📧 當前設定狀態

目前 .env 已設定為：
- ✅ SMTP 模式已啟用
- ✅ Gmail SMTP 伺服器設定
- ✅ 發件人設定為 yijiaxiang88@gmail.com
- ⚠️ 需要設定正確的應用程式密碼

下一步：請到 Gmail 產生應用程式密碼並更新 .env 中的 MAIL_PASSWORD 設定。
