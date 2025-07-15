# 點數累積系統實現

## 概述

本專案已實現滿百元消費累積一點數的功能，包含基本點數計算、生日當月雙倍點數、以及完整的點數交易記錄。

## 功能特色

### 🎯 核心功能
- **滿百元消費累積一點**：每消費 100 元獲得 1 點
- **生日當月雙倍點數**：生日當月購物享雙倍點數優惠
- **自動點數計算**：訂單建立時自動計算並累積點數
- **完整交易記錄**：記錄所有點數獲得和使用歷史

### 📊 計算規則
- 消費金額 ÷ 100 = 基本點數（取整數）
- 生日當月：基本點數 × 2
- 不足百元不累積點數
- 點數無上限，可持續累積

## 技術實現

### 後端實現

#### 1. 訂單控制器修改
在 `OrderController.php` 中添加點數計算邏輯：

```php
/**
 * 計算並累積點數
 * 規則：滿百元消費累積一點
 */
private function calculateAndAddPoints($order, $user)
{
    try {
        // 計算點數（滿百元消費累積一點）
        $points = intval($order->total / 100);
        
        if ($points > 0) {
            // 檢查是否為生日當月（雙倍點數）
            $isBirthdayMonth = $this->isBirthdayMonth($user);
            if ($isBirthdayMonth) {
                $points *= 2;
            }

            // 累積點數到用戶帳戶
            $user->addPoints($points, "購物消費獲得點數（訂單 #{$order->id}）" . ($isBirthdayMonth ? ' - 生日當月雙倍' : ''));

            // 記錄到點數交易記錄
            \App\Models\PointTransaction::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => 'earn',
                'description' => "購物消費獲得點數（訂單 #{$order->id}）" . ($isBirthdayMonth ? ' - 生日當月雙倍' : ''),
                'order_id' => $order->id,
            ]);

            \Log::info("用戶 {$user->id} 購物消費 {$order->total} 元，獲得 {$points} 點");
        }
    } catch (\Exception $e) {
        \Log::error("累積點數失敗：{$e->getMessage()}");
    }
}

/**
 * 檢查是否為生日當月
 */
private function isBirthdayMonth($user)
{
    if (!$user->birthday) {
        return false;
    }

    $birthday = \Carbon\Carbon::parse($user->birthday);
    $now = \Carbon\Carbon::now();

    return $birthday->month === $now->month;
}
```

#### 2. 訂單建立流程
在訂單建立成功後自動觸發點數計算：

```php
// 建立訂單
$order = Order::create([...]);

// 建立訂單項目
foreach ($cartItems as $item) {
    OrderItem::create([...]);
}

// 清空購物車
$cart->items()->delete();

// 計算並累積點數（滿百元消費累積一點）
$this->calculateAndAddPoints($order, $user);

DB::commit();
```

#### 3. 用戶模型方法
在 `User.php` 中已有點數相關方法：

```php
/**
 * 增加點數
 */
public function addPoints($points, $description = '')
{
    $this->increment('points', $points);
    
    // 記錄點數異動
    PointTransaction::create([
        'user_id' => $this->id,
        'points' => $points,
        'type' => 'earn',
        'description' => $description,
    ]);

    // 檢查是否升級
    $this->checkLevelUpgrade();
}
```

### 資料庫結構

#### 1. 用戶表 (users)
```sql
ALTER TABLE users ADD COLUMN points INT DEFAULT 0;
ALTER TABLE users ADD COLUMN birthday DATE NULL;
```

#### 2. 點數交易記錄表 (point_transactions)
```sql
CREATE TABLE point_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    points INT NOT NULL,
    type ENUM('earn', 'spend', 'expire', 'adjust') NOT NULL,
    description VARCHAR(200),
    order_id INT NULL,
    admin_id INT NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
);
```

## 計算範例

### 基本計算
| 消費金額 | 基本點數 | 生日當月 | 最終點數 |
|---------|---------|---------|---------|
| 80 元   | 0 點    | 否      | 0 點    |
| 150 元  | 1 點    | 否      | 1 點    |
| 250 元  | 2 點    | 否      | 2 點    |
| 300 元  | 3 點    | 是      | 6 點    |
| 500 元  | 5 點    | 否      | 5 點    |
| 800 元  | 8 點    | 是      | 16 點   |

### 程式碼範例
```php
// 計算點數
$amount = 350; // 消費金額
$basePoints = intval($amount / 100); // 3 點
$isBirthdayMonth = true; // 生日當月
$finalPoints = $isBirthdayMonth ? $basePoints * 2 : $basePoints; // 6 點
```

## API 端點

### 1. 建立訂單（自動累積點數）
```
POST /api/v1/orders
```

**請求範例：**
```json
{
    "recipient_name": "張三",
    "recipient_phone": "0912345678",
    "shipping_address": "台北市信義區...",
    "shipping_method": "宅配",
    "payment_method": "信用卡"
}
```

**回應範例：**
```json
{
    "success": true,
    "message": "訂單建立成功",
    "order_id": 123,
    "order": {
        "id": 123,
        "total": 350,
        "status": "pending",
        "items": [...]
    }
}
```

### 2. 查詢點數
```
GET /api/v1/points
```

**回應範例：**
```json
{
    "success": true,
    "points": 1250,
    "history": [
        {
            "id": 1,
            "type": "earn",
            "points": 3,
            "description": "購物消費獲得點數（訂單 #123）",
            "created_at": "2024-12-15T10:30:00Z"
        }
    ]
}
```

### 3. 查詢點數歷史
```
GET /api/v1/member/points/history
```

## 前端整合

### 1. 點數顯示
在會員中心顯示當前點數：

```vue
<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center">
      <div class="flex-shrink-0">
        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
          </svg>
        </div>
      </div>
      <div class="ml-4">
        <p class="text-sm font-medium text-gray-500">目前點數</p>
        <p class="text-2xl font-semibold text-gray-900">{{ statistics.current_points || 0 }}</p>
      </div>
    </div>
  </div>
</template>
```

### 2. 點數歷史記錄
顯示點數獲得和使用記錄：

```vue
<template>
  <div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
      <h3 class="text-lg font-medium text-gray-900">點數歷史記錄</h3>
    </div>
    <div class="divide-y divide-gray-200">
      <div v-for="record in pointTransactions" :key="record.id" class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-900">{{ record.description }}</p>
            <p class="text-xs text-gray-500">{{ formatDate(record.created_at) }}</p>
          </div>
          <div class="text-right">
            <span :class="[
              'text-sm font-medium',
              record.type === 'earn' ? 'text-green-600' : 'text-red-600'
            ]">
              {{ record.type === 'earn' ? '+' : '-' }}{{ record.points }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
```

## 測試方法

### 1. 使用測試頁面
訪問 `test-points-earning.html` 進行功能測試：

- 模擬訂單測試
- 點數計算器
- API 測試
- 各種測試案例

### 2. 測試步驟
1. 建立測試訂單
2. 檢查點數是否正確累積
3. 驗證生日當月雙倍功能
4. 查看點數交易記錄

### 3. 測試案例
- 消費 80 元 → 0 點
- 消費 150 元 → 1 點
- 消費 250 元 → 2 點
- 生日當月消費 300 元 → 6 點

## 監控與日誌

### 1. 日誌記錄
系統會記錄所有點數相關操作：

```php
\Log::info("用戶 {$user->id} 購物消費 {$order->total} 元，獲得 {$points} 點", [
    'user_id' => $user->id,
    'order_id' => $order->id,
    'order_total' => $order->total,
    'points_earned' => $points,
    'is_birthday_month' => $isBirthdayMonth
]);
```

### 2. 錯誤處理
完整的錯誤處理和日誌記錄：

```php
try {
    // 點數計算邏輯
} catch (\Exception $e) {
    \Log::error("累積點數失敗：{$e->getMessage()}", [
        'user_id' => $user->id,
        'order_id' => $order->id,
        'order_total' => $order->total
    ]);
}
```

## 未來改進

### 1. 功能增強
- [ ] 特殊節日雙倍點數
- [ ] 新會員首購加碼
- [ ] 推薦好友獎勵點數
- [ ] 點數到期提醒

### 2. 管理功能
- [ ] 點數規則管理
- [ ] 批量點數調整
- [ ] 點數統計報表
- [ ] 異常點數監控

### 3. 用戶體驗
- [ ] 點數獲得通知
- [ ] 點數即將到期提醒
- [ ] 點數使用建議
- [ ] 點數兌換推薦

## 相關檔案

- `backend/app/Http/Controllers/OrderController.php` - 訂單控制器
- `backend/app/Models/User.php` - 用戶模型
- `backend/app/Models/PointTransaction.php` - 點數交易模型
- `frontend/src/views/PointsView.vue` - 點數頁面
- `frontend/src/views/MemberCenterView.vue` - 會員中心
- `test-points-earning.html` - 測試頁面

## 注意事項

1. 點數計算在訂單建立時進行，確保資料一致性
2. 生日當月判斷基於用戶生日欄位
3. 所有點數操作都有完整的交易記錄
4. 系統會自動檢查會員等級升級
5. 建議定期備份點數交易記錄

---

**實現時間**：2024年12月
**版本**：1.0.0
**狀態**：✅ 完成基礎功能實現 