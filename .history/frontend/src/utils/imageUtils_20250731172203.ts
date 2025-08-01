/**
 * 統一的圖片 URL 處理函數
 * 處理前端和後端之間的圖片路徑轉換
 */
export function getImageUrl(imagePath: string | undefined | null): string {
  console.log('=== imageUtils Debug ===')
  console.log('Input imagePath:', imagePath)
  console.log('imagePath type:', typeof imagePath)
  
  if (!imagePath) {
    console.log('imagePath is empty, returning empty string')
    return ''
  }
  
  // 獲取 API 基礎 URL，優先使用環境變數
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
  console.log('apiBaseUrl:', apiBaseUrl)
  console.log('VITE_API_BASE_URL:', import.meta.env.VITE_API_BASE_URL)
  
  // 如果已經是完整的 HTTP URL，直接返回
  if (imagePath.startsWith('http')) {
    console.log('imagePath starts with http, returning as is:', imagePath)
    return imagePath
  }
  
  // 處理錯誤格式：/https://... (後端返回的 Cloudinary URL 前面多了一個 /)
  if (imagePath.startsWith('/http')) {
    const correctedPath = imagePath.substring(1) // 移除開頭的 /
    console.log('imagePath starts with /http, corrected to:', correctedPath)
    return correctedPath
  }
  
  // 處理 /storage 路徑
  if (imagePath.startsWith('/storage')) {
    const result = `${apiBaseUrl}${imagePath}`
    console.log('imagePath starts with /storage, result:', result)
    return result
  }
  
  // 如果是相對路徑，加上後端基礎 URL
  if (imagePath.startsWith('/')) {
    const result = `${apiBaseUrl}${imagePath}`
    console.log('imagePath starts with /, result:', result)
    return result
  }
  
  // 其他情況，直接返回原路徑
  console.log('returning original imagePath:', imagePath)
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