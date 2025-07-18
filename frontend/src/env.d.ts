/// <reference types="vite/client" />

interface ImportMetaEnv {
  readonly VITE_API_URL: string;
  // 其他自訂環境變數
}

interface ImportMeta {
  readonly env: ImportMetaEnv;
} 