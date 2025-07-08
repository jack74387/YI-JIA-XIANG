# 🏗️ A階段模擬執行說明

## 📋 概述
本目錄包含一佳香電商網站A階段（開發環境建置）的模擬執行腳本。

## 📁 檔案說明

### 1. `run-a-stage-simulation.sh` (Linux/macOS/Git Bash)
- **用途：** 在Linux、macOS或Windows Git Bash環境下執行A階段模擬
- **執行方式：**
  ```bash
  # 給予執行權限
  chmod +x run-a-stage-simulation.sh
  
  # 執行腳本
  ./run-a-stage-simulation.sh
  ```

### 2. `run-a-stage-simulation.ps1` (Windows PowerShell)
- **用途：** 在Windows PowerShell環境下執行A階段模擬
- **執行方式：**
  ```powershell
  # 執行腳本
  .\run-a-stage-simulation.ps1
  ```

### 3. `setup-development-environment.sh` (完整建置腳本)
- **用途：** 實際建置完整的開發環境（需要實際的伺服器環境）
- **注意：** 此腳本會實際安裝軟體和建立專案，請在測試環境中執行

## 🚀 快速開始

### Windows 用戶
1. 開啟 PowerShell
2. 切換到腳本目錄：
   ```powershell
   cd task/scripts
   ```
3. 執行模擬腳本：
   ```powershell
   .\run-a-stage-simulation.ps1
   ```

### Linux/macOS 用戶
1. 開啟終端機
2. 切換到腳本目錄：
   ```bash
   cd task/scripts
   ```
3. 執行模擬腳本：
   ```bash
   chmod +x run-a-stage-simulation.sh
   ./run-a-stage-simulation.sh
   ```

### Windows Git Bash 用戶
1. 開啟 Git Bash
2. 切換到腳本目錄：
   ```bash
   cd task/scripts
   ```
3. 執行模擬腳本：
   ```bash
   chmod +x run-a-stage-simulation.sh
   ./run-a-stage-simulation.sh
   ```

## 📅 模擬內容

腳本會模擬以下5天的開發環境建置過程：

### Day 1
- **上午：** 伺服器環境配置
- **下午：** 資料庫建置

### Day 2
- **上午：** 版本控制系統設定
- **下午：** 開發工具安裝

### Day 3
- **上午：** 前端框架選擇與設定
- **下午：** 後端框架選擇與設定

### Day 4
- **上午：** API架構設計
- **下午：** 資料庫結構設計

### Day 5
- **全天：** 第三方服務申請

## 🔧 技術棧展示

腳本會展示選擇的技術棧：

### 前端技術棧
- Vue.js 3.x
- TypeScript 5.x
- Vite 5.x
- Tailwind CSS 3.x
- Pinia 2.x
- Axios 1.x

### 後端技術棧
- Laravel 10.x
- MySQL 8.0
- PHP 8.1
- Redis 7.x
- Nginx 1.24

### 第三方服務
- 綠界科技（金流）
- 黑貓宅急便（物流）
- LINE Messaging API（社群整合）
- AWS S3（檔案儲存）

## 📊 輸出內容

執行腳本後，您將看到：

1. **詳細的時程安排** - 每天的工作內容
2. **具體的技術選擇** - 包含選擇理由
3. **完整的程式碼範例** - SQL、API路由等
4. **第三方服務申請流程** - 包含申請網址和所需文件
5. **完成檢查清單** - 可追蹤進度
6. **階段成果總結** - 交付物和成功指標

## ⚠️ 注意事項

1. **模擬性質：** 這些腳本僅為模擬展示，不會實際安裝軟體或建立檔案
2. **學習目的：** 主要用於了解A階段的完整流程和技術選擇
3. **實際執行：** 如需實際建置環境，請使用 `setup-development-environment.sh`
4. **環境要求：** 實際建置需要Linux伺服器環境

## 📚 相關文件

- **詳細實施計劃：** `../A階段-開發環境建置模擬實施計劃.md`
- **專案主文件：** `../../一佳香.md`
- **階段規劃：** `../02-網站建置與測試階段.md`

## 🎯 下一步

完成A階段模擬後，可以：
1. 查看B階段規劃：後台系統開發
2. 實際建置開發環境（使用完整建置腳本）
3. 開始功能開發工作

---

*最後更新：2025年1月* 