import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

interface MemberStatistics {
  total_orders: number
  total_spent: number
  current_points: number
  member_level: string
  member_level_color: string
  is_premium: boolean
}

interface PointTransaction {
  id: number
  user_id: number
  points: number
  type: 'earn' | 'spend' | 'expire'
  description: string
  order_id?: number
  expires_at?: string
  created_at: string
  type_name: string
  type_color: string
  formatted_points: string
}

interface Order {
  id: number
  order_number: string
  user_id: number
  total_amount: number
  shipping_fee: number
  discount_amount: number
  final_amount: number
  status: string
  payment_method: string
  shipping_method: string
  recipient_name: string
  recipient_phone: string
  recipient_address: string
  notes?: string
  created_at: string
  updated_at: string
  items: any[]
}

export const useMemberStore = defineStore('member', () => {
  const loading = ref(false)
  const error = ref<string | null>(null)
  
  const statistics = ref<MemberStatistics>({
    total_orders: 0,
    total_spent: 0,
    current_points: 0,
    member_level: '一般會員',
    member_level_color: 'text-gray-900',
    is_premium: false
  })
  
  const recentOrders = ref<Order[]>([])
  const pointTransactions = ref<PointTransaction[]>([])
  const userProfile = ref<any>(null)

  // 計算屬性
  const hasOrders = computed(() => recentOrders.value.length > 0)
  const hasPointTransactions = computed(() => pointTransactions.value.length > 0)

  // 取得會員統計資料
  const fetchStatistics = async () => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/v1/member/statistics')
      
      if (response.data.success) {
        statistics.value = response.data.statistics
        recentOrders.value = response.data.recent_orders
        pointTransactions.value = response.data.point_transactions
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || '載入統計資料失敗'
      console.error('載入統計資料失敗:', err)
    } finally {
      loading.value = false
    }
  }

  // 更新個人資料
  const updateProfile = async (profileData: any) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.put('http://127.0.0.1:8000/api/v1/member/profile', profileData)
      
      if (response.data.success) {
        userProfile.value = response.data.user
        return { success: true, message: '個人資料更新成功' }
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || '更新個人資料失敗'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  // 修改密碼
  const changePassword = async (passwordData: {
    current_password: string
    new_password: string
    new_password_confirmation: string
  }) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.put('http://127.0.0.1:8000/api/v1/member/password', passwordData)
      
      if (response.data.success) {
        return { success: true, message: '密碼修改成功' }
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || '修改密碼失敗'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  // 上傳頭像
  const uploadAvatar = async (file: File) => {
    loading.value = true
    error.value = null
    
    try {
      const formData = new FormData()
      formData.append('avatar', file)
      
      const response = await axios.post('http://127.0.0.1:8000/api/v1/member/avatar', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      
      if (response.data.success) {
        return { success: true, message: '頭像上傳成功', avatar_url: response.data.avatar_url }
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || '頭像上傳失敗'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  // 取得訂單列表
  const fetchOrders = async (page = 1) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/v1/member/orders?page=${page}`)
      
      if (response.data.success) {
        return response.data.orders
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || '載入訂單失敗'
      console.error('載入訂單失敗:', err)
    } finally {
      loading.value = false
    }
  }

  // 取得點數交易記錄
  const fetchPointHistory = async (page = 1) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/v1/member/points/history?page=${page}`)
      
      if (response.data.success) {
        return response.data.transactions
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || '載入點數記錄失敗'
      console.error('載入點數記錄失敗:', err)
    } finally {
      loading.value = false
    }
  }

  // 刪除帳戶
  const deleteAccount = async (password: string) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await axios.delete('http://127.0.0.1:8000/api/v1/member/account', {
        data: {
          password,
          confirmation: 'DELETE'
        }
      })
      
      if (response.data.success) {
        return { success: true, message: '帳戶已成功刪除' }
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || '刪除帳戶失敗'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  // 清除錯誤
  const clearError = () => {
    error.value = null
  }

  return {
    // 狀態
    loading,
    error,
    statistics,
    recentOrders,
    pointTransactions,
    userProfile,
    
    // 計算屬性
    hasOrders,
    hasPointTransactions,
    
    // 方法
    fetchStatistics,
    updateProfile,
    changePassword,
    uploadAvatar,
    fetchOrders,
    fetchPointHistory,
    deleteAccount,
    clearError
  }
}) 