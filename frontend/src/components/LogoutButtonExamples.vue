<template>
  <div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">登出按鈕設計範例</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- 基本登出按鈕 -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">基本登出按鈕</h2>
          <div class="space-y-4">
            <LogoutButton variant="outline" text="登出" />
            <LogoutButton variant="solid" text="登出帳戶" />
            <SimpleLogoutButton text="快速登出" />
          </div>
        </div>

        <!-- 不同尺寸 -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">不同尺寸</h2>
          <div class="space-y-4">
            <LogoutButton size="sm" variant="outline" text="小按鈕" />
            <LogoutButton size="md" variant="outline" text="中按鈕" />
            <SimpleLogoutButton text="簡單按鈕" />
          </div>
        </div>

        <!-- 確認對話框版本 -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">確認對話框版本</h2>
          <div class="space-y-4">
            <LogoutButton 
              variant="outline" 
              text="安全登出" 
              :show-confirm="true"
              @logout="handleLogoutWithConfirm"
            />
            <SimpleLogoutButton 
              text="確認登出" 
              :show-confirm="true"
              @logout="handleLogoutWithConfirm"
            />
          </div>
        </div>

        <!-- 浮動按鈕 -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">浮動按鈕</h2>
          <p class="text-gray-600 mb-4">浮動按鈕會顯示在頁面右下角</p>
          <button 
            @click="toggleFloatingButton"
            class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-md"
          >
            {{ showFloatingButton ? '隱藏' : '顯示' }} 浮動按鈕
          </button>
        </div>
      </div>

      <!-- 使用說明 -->
      <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">使用說明</h2>
        <div class="prose max-w-none">
          <h3 class="text-lg font-medium text-gray-900 mb-2">組件列表</h3>
          <ul class="list-disc list-inside space-y-2 text-gray-600">
            <li><strong>LogoutButton</strong> - 功能完整的登出按鈕，支援多種樣式和確認對話框</li>
            <li><strong>SimpleLogoutButton</strong> - 簡化的登出按鈕，適合快速使用</li>
            <li><strong>FloatingLogoutButton</strong> - 浮動式登出按鈕，包含用戶選單</li>
            <li><strong>LogoutConfirmDialog</strong> - 登出確認對話框</li>
          </ul>

          <h3 class="text-lg font-medium text-gray-900 mb-2 mt-4">Props 說明</h3>
          <div class="bg-gray-50 rounded-lg p-4">
            <pre class="text-sm text-gray-700"><code>// LogoutButton Props
variant: 'solid' | 'outline'  // 按鈕樣式
size: 'sm' | 'md'            // 按鈕尺寸
text: string                 // 按鈕文字
showConfirm: boolean         // 是否顯示確認對話框

// 事件
@logout: () => void          // 登出成功時觸發
@error: (error: any) => void // 登出失敗時觸發</code></pre>
          </div>

          <h3 class="text-lg font-medium text-gray-900 mb-2 mt-4">使用範例</h3>
          <div class="bg-gray-50 rounded-lg p-4">
            <pre class="text-sm text-gray-700"><code>&lt;!-- 基本使用 --&gt;
&lt;LogoutButton /&gt;

&lt;!-- 自訂樣式 --&gt;
&lt;LogoutButton 
  variant="solid" 
  size="sm" 
  text="登出帳戶" 
/&gt;

&lt;!-- 確認對話框 --&gt;
&lt;LogoutButton 
  :show-confirm="true"
  @logout="handleLogout"
/&gt;

&lt;!-- 浮動按鈕 --&gt;
&lt;FloatingLogoutButton :show="true" /&gt;</code></pre>
          </div>
        </div>
      </div>
    </div>

    <!-- 浮動登出按鈕 -->
    <FloatingLogoutButton :show="showFloatingButton" />

    <!-- 登出確認對話框 -->
    <LogoutConfirmDialog 
      :show="showLogoutDialog" 
      @close="showLogoutDialog = false" 
    />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import LogoutButton from './LogoutButton.vue'
import SimpleLogoutButton from './SimpleLogoutButton.vue'
import FloatingLogoutButton from './FloatingLogoutButton.vue'
import LogoutConfirmDialog from './LogoutConfirmDialog.vue'

const router = useRouter()

const showFloatingButton = ref(false)
const showLogoutDialog = ref(false)

const toggleFloatingButton = () => {
  showFloatingButton.value = !showFloatingButton.value
}

const handleLogoutWithConfirm = () => {
  showLogoutDialog.value = true
}
</script>

<style scoped>
/* 使用 Tailwind CSS */
</style> 