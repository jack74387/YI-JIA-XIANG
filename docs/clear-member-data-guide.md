# 清除會員資料指南

## 概述

本指南提供多種方式來清除一佳香系統中的會員註冊資料，適用於開發測試、資料重置等場景。

## 清除方式

### 1. 使用 Artisan 命令（推薦）

#### 基本用法
```bash
php artisan member:clear
```

#### 強制執行（跳過確認）
```bash
php artisan member:clear --force
```

#### 功能說明
- 清除所有會員帳號（保留管理員）
- 清除所有訂單資料
- 清除所有購物車資料
- 清除所有點數交易記錄
- 清除所有用戶優惠券記錄
- 清除所有操作日誌
- 使用資料庫事務確保資料一致性

### 2. 使用獨立腳本

#### 執行腳本
```bash
php clear-member-data.php
```

#### 功能說明
- 與 Artisan 命令功能相同
- 提供互動式確認
- 顯示詳細的清除統計

### 3. 使用 Tinker（快速清除）

#### 只清除會員帳號
```bash
php artisan tinker --execute="App\Models\User::where('is_admin', false)->delete(); echo '已清除會員帳號';"
```

#### 清除所有用戶資料
```bash
php artisan tinker --execute="App\Models\User::truncate(); echo '已清除所有用戶資料';"
```

## 清除範圍

### 會清除的資料
- ✅ 會員帳號（非管理員）
- ✅ 用戶訂單
- ✅ 購物車資料
- ✅ 購物車項目
- ✅ 點數交易記錄
- ✅ 用戶優惠券記錄
- ✅ 操作日誌

### 會保留的資料
- ✅ 管理員帳號
- ✅ 產品資料
- ✅ 分類資料
- ✅ 優惠券定義
- ✅ 系統設定

## 安全注意事項

### ⚠️ 重要提醒
1. **此操作不可逆**：清除的資料無法恢復
2. **影響範圍廣**：會清除所有會員相關資料
3. **建議備份**：執行前請先備份資料庫
4. **測試環境**：建議在測試環境中先試用

### 🔒 安全措施
- 使用資料庫事務確保一致性
- 提供確認機制防止誤操作
- 保留管理員帳號確保系統可用性
- 詳細的執行日誌

## 使用場景

### 開發測試
- 清除測試資料
- 重置系統狀態
- 準備新的測試環境

### 系統維護
- 清理無效資料
- 系統重置
- 資料庫優化

### 演示準備
- 準備乾淨的演示環境
- 移除敏感資料
- 系統初始化

## 備份建議

### 資料庫備份
```bash
# MySQL 備份
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# SQLite 備份
cp database/database.sqlite backup_database_$(date +%Y%m%d_%H%M%S).sqlite
```

### 選擇性備份
```bash
# 只備份用戶資料
php artisan tinker --execute="
    $users = App\Models\User::all();
    file_put_contents('users_backup.json', $users->toJson());
    echo '用戶資料已備份到 users_backup.json';
"
```

## 故障排除

### 常見問題

#### 1. 權限不足
```
錯誤：SQLSTATE[42000]: Syntax error or access violation
解決：檢查資料庫用戶權限，確保有 DELETE 和 TRUNCATE 權限
```

#### 2. 外鍵約束
```
錯誤：SQLSTATE[23000]: Integrity constraint violation
解決：檢查外鍵約束，可能需要調整清除順序
```

#### 3. 記憶體不足
```
錯誤：Allowed memory size exhausted
解決：增加 PHP 記憶體限制或分批處理
```

### 恢復方案

#### 從備份恢復
```bash
# MySQL 恢復
mysql -u username -p database_name < backup_file.sql

# SQLite 恢復
cp backup_database.sqlite database/database.sqlite
```

#### 重新初始化
```bash
# 重新執行遷移
php artisan migrate:fresh --seed

# 重新建立管理員帳號
php artisan tinker --execute="
    App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'is_admin' => true
    ]);
"
```

## 最佳實踐

### 執行前檢查
1. 確認當前環境（開發/測試/生產）
2. 檢查資料庫連接
3. 確認備份完整性
4. 通知相關人員

### 執行後驗證
1. 檢查系統功能正常
2. 確認管理員帳號可用
3. 驗證資料清除完整性
4. 更新相關文檔

### 定期維護
1. 定期清理測試資料
2. 監控資料庫大小
3. 優化資料庫效能
4. 更新清除腳本

## 聯繫支援

如果在使用過程中遇到問題，請：
1. 檢查錯誤日誌
2. 參考故障排除指南
3. 聯繫技術支援團隊
4. 提供詳細的錯誤資訊 