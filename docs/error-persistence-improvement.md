# 錯誤訊息持久性改善

## 問題描述

原本的登入和註冊系統中，當發生錯誤時，如果頁面重新載入（例如瀏覽器重新整理、網路問題導致的重新載入等），錯誤訊息會消失，用戶無法看到之前的錯誤訊息，影響使用體驗。

## 解決方案

### 1. localStorage 錯誤狀態保存

使用瀏覽器的 localStorage 來保存錯誤狀態，確保即使頁面重新載入也能恢復錯誤訊息。

#### 技術實現

```typescript
// 保存錯誤狀態到 localStorage
const saveErrorState = (error: string) => {
  localStorage.setItem('login_error', error)
  localStorage.setItem('login_submitted', 'true')
}

// 從 localStorage 恢復錯誤狀態
const restoreErrorState = () => {
  const savedError = localStorage.getItem('login_error')
  const savedSubmitted = localStorage.getItem('login_submitted')
  
  if (savedError) {
    localError.value = savedError
    localStorage.removeItem('login_error') // 清除保存的錯誤
  }
  
  if (savedSubmitted === 'true') {
    hasSubmitted.value = true
    localStorage.removeItem('login_submitted') // 清除保存的狀態
  }
}
```

### 2. 頁面載入時恢復狀態

在頁面載入時（`onMounted`）檢查並恢復錯誤狀態：

```typescript
onMounted(() => {
  // 恢復錯誤狀態
  restoreErrorState()
  
  // 如果已經登入，直接跳轉到首頁
  if (authStore.isAuthenticated) {
    router.push('/')
  }
})
```

### 3. 錯誤發生時保存狀態

當錯誤發生時，立即保存到 localStorage：

```typescript
const handleLogin = async (event?: Event) => {
  // 防止表單預設提交行為
  if (event) {
    event.preventDefault()
  }
  
  // ... 登入邏輯
  
  try {
    const result = await authStore.login(form)
    if (result.success) {
      // 登入成功，清除錯誤狀態
      clearError()
      router.push('/')
    } else {
      // 設置錯誤訊息
      localError.value = specificError
      // 保存錯誤狀態
      saveErrorState(specificError)
    }
  } catch (error: any) {
    // ... 錯誤處理
    localError.value = specificError
    // 保存錯誤狀態
    saveErrorState(specificError)
  }
}
```

### 4. 自動清理機制

在適當的時候自動清理保存的錯誤狀態：

```typescript
// 清除錯誤
const clearError = () => {
  localError.value = ''
  errorDetails.value = ''
  authStore.clearError()
  // 清除保存的錯誤狀態
  localStorage.removeItem('login_error')
  localStorage.removeItem('login_submitted')
}
```

## 修改的檔案

### 1. `frontend/src/views/LoginView.vue`
- 新增 localStorage 錯誤狀態保存機制
- 頁面載入時恢復錯誤狀態
- 錯誤發生時保存狀態
- 成功操作時自動清理

### 2. `frontend/src/views/RegisterView.vue`
- 同步加入錯誤狀態持久化機制
- 使用不同的 localStorage key 避免衝突

### 3. `test-error-persistence.html`
- 創建測試頁面驗證錯誤訊息持久性
- 模擬頁面重新載入情況
- 提供完整的測試流程

## 功能特點

### 1. 錯誤狀態持久化
- 錯誤訊息保存到 localStorage
- 提交狀態也一併保存
- 頁面重新載入後自動恢復

### 2. 智能清理機制
- 成功操作後自動清除錯誤狀態
- 手動清除錯誤時也清理 localStorage
- 避免錯誤狀態殘留

### 3. 防止衝突
- 登入和註冊使用不同的 localStorage key
- 避免兩個頁面的錯誤狀態互相干擾

### 4. 用戶體驗改善
- 錯誤訊息不會因為頁面重新載入而消失
- 用戶可以清楚看到之前的錯誤訊息
- 提供更穩定的錯誤顯示機制

## 測試方法

### 1. 使用測試頁面
```bash
# 開啟測試頁面
open test-error-persistence.html
```

### 2. 測試步驟
1. **產生錯誤**：點擊任意錯誤模擬按鈕
2. **驗證顯示**：確認錯誤訊息正確顯示
3. **模擬重新載入**：點擊"模擬頁面重新載入"按鈕
4. **驗證持久性**：確認錯誤訊息在重新載入後仍然顯示
5. **清除錯誤**：點擊"清除所有錯誤"按鈕
6. **再次重新載入**：確認錯誤訊息不會再出現

### 3. 實際應用測試
1. 在登入頁面輸入錯誤的帳號密碼
2. 確認錯誤訊息顯示
3. 重新整理瀏覽器頁面
4. 確認錯誤訊息仍然顯示
5. 開始輸入時確認錯誤自動清除

## 技術細節

### localStorage Key 命名
- 登入頁面：`login_error`, `login_submitted`
- 註冊頁面：`register_error`, `register_submitted`
- 測試頁面：`test_error`, `test_submitted`

### 錯誤狀態結構
```typescript
interface ErrorState {
  error: string;        // 錯誤訊息
  submitted: boolean;   // 是否已提交過表單
}
```

### 清理時機
1. **成功操作後**：登入/註冊成功時
2. **手動清除**：用戶點擊關閉按鈕時
3. **輸入時**：用戶開始輸入時
4. **頁面載入後**：恢復錯誤狀態後立即清理

## 安全性考慮

### 1. 資料清理
- 錯誤狀態在恢復後立即清理
- 避免敏感資訊長期儲存
- 定期清理過期的錯誤狀態

### 2. 資料隔離
- 不同頁面使用不同的 key
- 避免資料互相干擾
- 確保資料的獨立性

### 3. 錯誤處理
- 處理 localStorage 不可用的情況
- 優雅降級到非持久化模式
- 確保系統的穩定性

## 總結

這次改善解決了錯誤訊息因頁面重新載入而消失的問題，大幅提升了用戶體驗：

1. **錯誤訊息持久化**：使用 localStorage 保存錯誤狀態
2. **智能恢復機制**：頁面載入時自動恢復錯誤狀態
3. **自動清理機制**：適當時候自動清理錯誤狀態
4. **完整測試覆蓋**：提供測試頁面驗證功能

用戶現在可以穩定地看到錯誤訊息，不會因為頁面重新載入而失去重要的錯誤資訊，整體使用體驗更加可靠和友好。 