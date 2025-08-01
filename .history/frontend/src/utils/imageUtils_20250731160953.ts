/**
 * 統一的圖片 URL 處理函數
 * 處理前端和後端之間的圖片路徑轉換
 */
export function getImageUrl(imagePath: string | undefined | null): string {
  if (!imagePath) return ''
  
  // 如果已經是完整的 HTTP URL，直接返回
  if (imagePath.startsWith('http')) return imagePath
  
  // 獲取 API 基礎 URL，優先使用環境變數
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
  
  // 處理 /storage 路徑
  if (imagePath.startsWith('/storage')) {
    return `${apiBaseUrl}${imagePath}`
  }
  
  // 如果是相對路徑，加上後端基礎 URL
  if (imagePath.startsWith('/')) {
    return `${apiBaseUrl}${imagePath}`
  }
  
  // 其他情況，直接返回原路徑
  return imagePath
}

/**
 * 檢查圖片是否載入成功
 */
export function checkImageLoad(url: string): Promise<boolean> {
  return new Promise((resolve) => {
    const img = new Image()
    img.onload = () => resolve(true)
    img.onerror = () => resolve(false)
    img.src = url
  })
}

/**
 * 獲取圖片尺寸
 */
export function getImageDimensions(url: string): Promise<{ width: number; height: number }> {
  return new Promise((resolve, reject) => {
    const img = new Image()
    img.onload = () => {
      resolve({ width: img.naturalWidth, height: img.naturalHeight })
    }
    img.onerror = () => {
      reject(new Error('圖片載入失敗'))
    }
    img.src = url
  })
} 