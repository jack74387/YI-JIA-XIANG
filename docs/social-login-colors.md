# 社交登入按鈕顏色設計

## 設計原則

本專案的社交登入按鈕採用各平台的官方品牌色彩，確保視覺一致性和品牌識別度。

## 按鈕顏色規格

### 1. Google 登入按鈕
```css
/* 按鈕樣式 */
background-color: #ffffff; /* 白色背景 */
border: 1px solid #d1d5db; /* 灰色邊框 */
color: #374151; /* 深灰色文字 */

/* Hover 狀態 */
hover:background-color: #f9fafb; /* 淺灰色背景 */

/* Focus 狀態 */
focus:ring-color: #3b82f6; /* 藍色聚焦環 */
```

**圖標顏色**：使用 Google 官方四色圖標
- 藍色：#4285F4
- 綠色：#34A853
- 黃色：#FBBC05
- 紅色：#EA4335

### 2. Facebook 登入按鈕
```css
/* 按鈕樣式 */
background-color: #1877f2; /* Facebook 官方藍色 */
border: 1px solid transparent; /* 透明邊框 */
color: #ffffff; /* 白色文字 */

/* Hover 狀態 */
hover:background-color: #166fe5; /* 深藍色背景 */

/* Focus 狀態 */
focus:ring-color: #1877f2; /* 藍色聚焦環 */
```

**圖標顏色**：白色（currentColor）

### 3. LINE 登入按鈕
```css
/* 按鈕樣式 */
background-color: #00b900; /* LINE 官方綠色 */
border: 1px solid transparent; /* 透明邊框 */
color: #ffffff; /* 白色文字 */

/* Hover 狀態 */
hover:background-color: #00a000; /* 深綠色背景 */

/* Focus 狀態 */
focus:ring-color: #00b900; /* 綠色聚焦環 */
```

**圖標顏色**：白色（currentColor）

## Tailwind CSS 類別

### Google 登入
```html
class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
```

### Facebook 登入
```html
class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
```

### LINE 登入
```html
class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm bg-green-500 text-sm font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
```

## 顏色變更歷史

### 2024年12月 - LINE 登入按鈕顏色更新
- **變更前**：白色背景，灰色文字，灰色邊框
- **變更後**：綠色背景，白色文字，透明邊框
- **原因**：更符合 LINE 官方品牌色彩，提升視覺一致性

## 設計考量

### 1. 品牌一致性
- 使用各平台的官方品牌色彩
- 保持圖標和背景的視覺和諧
- 確保品牌識別度

### 2. 可訪問性
- 確保文字與背景的對比度符合 WCAG 標準
- 提供清晰的 focus 狀態指示
- 支援鍵盤導航

### 3. 用戶體驗
- 一致的按鈕尺寸和間距
- 平滑的 hover 和 focus 動畫
- 清晰的視覺層次

### 4. 響應式設計
- 在不同螢幕尺寸下保持一致的視覺效果
- 觸控設備友好的按鈕尺寸
- 適應不同顯示密度的螢幕

## 自定義選項

### 修改顏色主題
如果需要修改按鈕顏色，可以：

1. **更改背景色**：修改 `bg-*` 類別
2. **更改文字色**：修改 `text-*` 類別
3. **更改邊框色**：修改 `border-*` 類別
4. **更改 hover 色**：修改 `hover:bg-*` 類別
5. **更改 focus 色**：修改 `focus:ring-*` 類別

### 範例：自定義 LINE 登入按鈕
```html
<!-- 使用深綠色主題 -->
<button class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm bg-green-700 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors duration-200">
  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
    <!-- LINE 圖標 -->
  </svg>
  <span class="ml-2">LINE 登入</span>
</button>
```

## 測試建議

### 1. 視覺測試
- 在不同瀏覽器中檢查顏色顯示
- 驗證 hover 和 focus 狀態
- 確認動畫效果流暢

### 2. 可訪問性測試
- 使用色彩對比度檢查工具
- 測試鍵盤導航功能
- 驗證螢幕閱讀器支援

### 3. 響應式測試
- 在不同設備上測試顯示效果
- 確認觸控操作體驗
- 驗證各種螢幕尺寸的適配性

## 相關檔案

- `frontend/src/views/LoginView.vue` - 主要登入頁面
- `test-social-login.html` - 測試頁面
- `docs/social-login-implementation.md` - 實現文檔
- `docs/social-login-summary.md` - 總結文檔

---

**最後更新**：2024年12月
**版本**：1.1.0 