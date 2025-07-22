# Docker Build 修正重點紀錄

## 1. 問題起因
- 根據 `docs/docker` 說明進行 Docker build，映像檔名稱為 `hejianhang_final`。
- 初期 build 卡在 TypeScript 型別錯誤（如 prisma/seed.ts 衝突、巢狀物件屬性為 null/undefined、unknown 型別渲染等）。

## 2. 型別錯誤自動修正策略
- **巢狀物件屬性存取**：所有如 `order.user`、`assignment.worker`、`assignment.vendor`、`createdBy` 等巢狀物件，存取前一律加上 null 判斷與預設值（如 `order.user ? order.user.firstName : ''` 或 `order.user?.firstName || ''`）。
- **string | null 指派給 string**：所有 `string | null` 指派給 `string` 的情境（如 `customerId: order.userId`），一律加上 `|| ''` 預設值。
- **function 參數型別**：若 function 參數型別為 `string`，但來源可能為 `string | null`，一律加上 `|| ''`。
- **API 輸入/表單資料**：所有表單、API 輸入、Prisma 查詢結果等，回傳或傳遞時也加上 `|| ''`。

## 3. interface 統一
- 多個前端頁面（如 workers/orders/assigned-to-vendors/page.tsx、workers/orders/page.tsx 等）與共用元件（WorkersOrderList）之間的型別不一致，已自動統一 interface，確保 worker 物件都包含 `account` 屬性。
- 修正 `OrderItem.assignments.worker` 型別，讓所有 interface 一致，避免型別衝突。

## 4. unknown 型別渲染 ReactNode 問題
- 修正所有將 unknown 型別直接渲染為 ReactNode 的情境，統一用 `String(value)` 包裝。
- 例如：`<li key={key}>{key}: {String(value)}</li>`

## 5. 批次修正流程
- 每次 build 失敗後，根據最新錯誤訊息自動定位並修正所有同類型錯誤，然後再次 build。
- 修正範圍涵蓋 API route、前端頁面、共用元件、型別定義等，並確保所有巢狀物件、function 參數、表單預設值、API 輸入等都符合型別要求。
- 若遇到 linter error，會自動再次修正，並避免無意義的重複修正循環。

## 6. 主要修正檔案與範例
- `src/components/orders/ProductPriceEditor.tsx`
  - 修正 Object.entries(specs) 的 value 型別為 unknown，改為 `String(value)`。
- `src/lib/notifications/service.ts`
  - `customerId: order.userId` 改為 `customerId: order.userId || ''`。
- `src/app/orders/[id]/page.tsx`、`src/app/admin/orders/[id]/page.tsx`、`src/app/counter/orders/[id]/page.tsx`、`src/app/workers/orders/page.tsx`、`src/app/workers/orders/in-progress/page.tsx`、`src/app/workers/page.tsx`
  - 所有巢狀物件屬性（如 user.firstName、vendor.name、worker.account 等）渲染時一律加上 `|| ''` 或 `|| '未知'`。
- 其他所有出現 string | null 指派給 string 的情境，皆已自動加上預設值。

### 修正範例
```tsx
// 巢狀物件屬性渲染
{order.user?.firstName || ''}
{assignment.vendor?.name || '未知'}

// unknown 型別渲染
<li key={key}>{key}: {String(value)}</li>

// string | null 指派給 string
customerId: order.userId || ''
```

## 7. 最終 build 成功結論
- 所有型別錯誤與相關問題皆已自動修正，Docker build 已順利完成，映像檔 `hejianhang_final` 成功建立。
- 修正過程全自動批次進行，無需手動逐一修改。
- 若未來遇到同類型錯誤，建議持續採用上述自動修正策略。 

---

## 8. Docker Build/Migration 實戰問題與解法

### 問題現象
- `docker compose up` 會自動 build，若 image 名稱與 build 不一致會重複 build。
- Prisma migration/seed 在容器內找不到 table，出現 `table ... does not exist`。
- 容器內 `prisma/migrations` 目錄為空，導致 migration deploy 無效。
- `.env` 的 `DATABASE_URL` 在本機與容器需分別設為 `localhost` 與 `postgres`。
- `.dockerignore` 若有 `prisma/migrations/`，build 時 migrations 目錄不會被複製進 image。

### 解決流程
1. **image 名稱一致**：
   - `docker-compose.yml` 的 `app.image` 要與 build 時的 tag 一致（如 `hejianhang_final`）。
2. **migration/seed 正確流程**：
   - 本機 `.env` 設為 `localhost`，執行 `npx prisma migrate dev --name init` 產生 migration。
   - 產生後改回 `postgres`，build image。
   - 確認 `.dockerignore` 沒有排除 `prisma/migrations/`。
   - build image → up app → deploy migration → seed。
3. **檢查容器內 migrations**：
   - 用 `docker exec -it <container> ls prisma/migrations` 確認 migrations 有被複製進去。
4. **.dockerignore 注意事項**：
   - 不可有 `prisma/migrations/`，否則 migration 永遠不會進 image。
5. **遇到 table 不存在**：
   - 代表 migration 沒有 deploy 到容器資料庫，需檢查 build context 及 .dockerignore。
6. **重啟流程**：
   - `docker compose down` → `docker build -t <image> .` → `docker compose up -d app` → deploy/seed。

### 實戰建議
- migration/seed 只在本機產生，容器內只做 deploy/seed。
- 每次 schema 變更都要重新 build image，確保 migrations 目錄同步。
- 若遇到 prisma 相關 table 不存在，優先檢查 .dockerignore 與 build context。
- 建議將這些重點寫入專案 README 或內部 wiki，避免團隊踩雷。

--- 