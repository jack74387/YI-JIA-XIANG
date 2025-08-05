<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-6 bg-gray-50">
      <h1 class="text-2xl font-bold mb-4">文章管理</h1>
      <!-- 新增文章按鈕 -->
      <div class="mb-6">
        <button class="btn-admin" @click="openCreate">新增文章</button>
      </div>

      <!-- 文章列表 -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">標題</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">狀態</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">發布時間</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">操作</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="a in articles" :key="a.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-20">
                  {{ a.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div class="max-w-xs truncate" :title="a.title">
                    {{ a.title }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap w-24">
                  <span 
                    :class="getStatusClass(a.status)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getStatusText(a.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 w-32">
                  {{ a.published_at ? formatDate(a.published_at) : '' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium w-48">
                  <div class="flex space-x-2">
                    <button 
                      class="text-amber-600 hover:text-amber-900 font-medium"
                      @click="openEdit(a)"
                    >
                      編輯
                    </button>
                    <button 
                      class="text-red-600 hover:text-red-900 font-medium"
                      @click="del(a.id)"
                    >
                      刪除
                    </button>
                    <button 
                      v-if="a.status==='published'" 
                      class="text-blue-600 hover:text-blue-900 font-medium"
                      @click="publishFB(a.id)"
                    >
                      發布到FB
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-start justify-center z-50 overflow-y-auto pt-8 pb-8" @click="closeModal">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl relative mx-4 my-auto" @click.stop>
          <h2 class="text-xl font-bold mb-4">{{ editing ? '編輯' : '新增' }}文章</h2>
          <form @submit.prevent="save">
            <div class="mb-3">
              <label class="block mb-1">標題</label>
              <input v-model="form.title" class="input-field w-full" required />
            </div>
            <div class="mb-3">
              <label class="block mb-1">內文</label>
              <textarea v-model="form.content" class="input-field w-full h-32" required />
            </div>
            <div class="mb-3">
              <label class="block mb-1">圖片（可多選）</label>
              <input type="file" multiple accept="image/*" @change="uploadImages" />
              <div class="flex flex-wrap gap-2 mt-2">
                <div v-for="(img, idx) in form.images" :key="img" class="relative group">
                  <img :src="getImageUrl(img)" class="w-20 h-20 object-contain rounded border bg-gray-100" />
                  <button type="button"
                    class="absolute top-0 right-0 bg-white bg-opacity-80 rounded-full p-1 m-1 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow"
                    style="display: none;"
                    @click="removeImage(idx)"
                    :disabled="imgLoadingIdx === idx"
                    v-show="true"
                    @mouseenter="hoverImgIdx = idx" @mouseleave="hoverImgIdx = null"
                  >
                    <span v-if="imgLoadingIdx === idx" class="loader w-4 h-4"></span>
                    <span v-else>✕</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="block mb-1">影片（可多選）</label>
              <input type="file" multiple accept="video/*" @change="uploadVideos" />
              <div class="flex flex-wrap gap-2 mt-2">
                <div v-for="(vid, idx) in form.videos" :key="vid" class="relative group">
                  <video :src="getImageUrl(vid)" class="w-24 h-16 rounded border" controls />
                  <button type="button"
                    class="absolute top-0 right-0 bg-white bg-opacity-80 rounded-full p-1 m-1 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow"
                    @click="removeVideo(idx)"
                    :disabled="vidLoadingIdx === idx"
                    v-show="true"
                    @mouseenter="hoverVidIdx = idx" @mouseleave="hoverVidIdx = null"
                  >
                    <span v-if="vidLoadingIdx === idx" class="loader w-4 h-4"></span>
                    <span v-else>✕</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="block mb-1">狀態</label>
              <select v-model="form.status" class="input-field">
                <option value="draft">草稿</option>
                <option value="published">已發布</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="block mb-1">發布時間</label>
              <input type="datetime-local" v-model="form.published_at" class="input-field" />
            </div>
            <div class="flex gap-2 justify-end">
              <button type="button" class="btn-admin-sm" @click="closeModal">取消</button>
              <button type="submit" class="btn-admin-sm bg-green-600 hover:bg-green-700">儲存</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminSidebar from './AdminSidebar.vue'
import { getImageUrl } from '@/utils/imageUtils'
const articles = ref<any[]>([])
const showModal = ref(false)
const editing = ref(false)
const form = ref({
  id: null,
  title: '',
  content: '',
  images: [] as string[],
  images_public_ids: [] as string[], // 新增：跟踪圖片的 public_id
  videos: [] as string[],
  videos_public_ids: [] as string[], // 新增：跟踪影片的 public_id
  status: 'draft',
  published_at: ''
})
const imgLoadingIdx = ref<number|null>(null)
const vidLoadingIdx = ref<number|null>(null)
const hoverImgIdx = ref<number|null>(null)
const hoverVidIdx = ref<number|null>(null)
function fetchArticles() {
  axios.get('/api/v1/admin/articles').then(res => {
    articles.value = res.data.data.data || []
  })
}
function openCreate() {
  editing.value = false
  showModal.value = true
  form.value = { 
    id: null, 
    title: '', 
    content: '', 
    images: [], 
    images_public_ids: [],
    videos: [], 
    videos_public_ids: [],
    status: 'draft', 
    published_at: '' 
  }
}
function openEdit(a: any) {
  editing.value = true
  showModal.value = true
  form.value = { 
    ...a, 
    images: a.images || [], 
    images_public_ids: a.images_public_ids || [],
    videos: a.videos || [], 
    videos_public_ids: a.videos_public_ids || [],
    published_at: a.published_at ? a.published_at.replace(' ', 'T') : '' 
  }
}
function closeModal() { showModal.value = false }
function save() {
  const payload = { ...form.value, images: form.value.images, videos: form.value.videos }
  if (editing.value && form.value.id) {
    axios.put(`/api/v1/admin/articles/${form.value.id}`, payload).then(() => { closeModal(); fetchArticles() })
  } else {
    axios.post('/api/v1/admin/articles', payload).then(() => { closeModal(); fetchArticles() })
  }
}
function del(id: number) {
  if (confirm('確定刪除？此操作將同時刪除文章中的所有圖片和影片，且無法復原。')) {
    axios.delete(`/api/v1/admin/articles/${id}`)
      .then(response => {
        fetchArticles();
        // 顯示刪除成功訊息
        const message = response.data.message || '文章刪除成功';
        alert(message);
      })
      .catch(error => {
        console.error('刪除文章失敗:', error);
        const message = error.response?.data?.message || '刪除文章失敗';
        alert('錯誤: ' + message);
      });
  }
}
async function uploadImages(e: any) {
  const files = Array.from(e.target.files) as File[];
  if (!files.length) return;

  for (const file of files) {
    try {
      const formData = new FormData();
      formData.append('image', file);

      const res = await axios.post('/api/v1/admin/articles/upload-image', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      if (res.data.success && res.data.url) {
        // 確保 images_public_ids 陣列存在
        if (!Array.isArray(form.value.images_public_ids)) {
          form.value.images_public_ids = [];
        }
        
        form.value.images.push(res.data.url);
        form.value.images_public_ids.push(res.data.public_id); // 保存 public_id
        
        console.log('文章圖片上傳成功:', {
          url: res.data.url,
          public_id: res.data.public_id,
          total_images: form.value.images.length,
          total_public_ids: form.value.images_public_ids.length
        });
      } else {
        throw new Error(res.data.message || '上傳失敗');
      }
    } catch (error: any) {
      console.error(`圖片 ${file.name} 上傳失敗:`, error);
      alert(`圖片 ${file.name} 上傳失敗: ` + (error.response?.data?.message || error.message || '網路錯誤'));
    }
  }
  e.target.value = ''; // 清空 input
}

async function uploadVideos(e: any) {
  const files = Array.from(e.target.files) as File[];
  if (!files.length) return;

  for (const file of files) {
    try {
      const formData = new FormData();
      formData.append('video', file);

      const res = await axios.post('/api/v1/admin/articles/upload-video', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      if (res.data.success && res.data.url) {
        // 確保 videos_public_ids 陣列存在
        if (!Array.isArray(form.value.videos_public_ids)) {
          form.value.videos_public_ids = [];
        }
        
        form.value.videos.push(res.data.url);
        form.value.videos_public_ids.push(res.data.public_id); // 保存 public_id
        
        console.log('文章影片上傳成功:', {
          url: res.data.url,
          public_id: res.data.public_id,
          total_videos: form.value.videos.length,
          total_public_ids: form.value.videos_public_ids.length
        });
      } else {
        throw new Error(res.data.message || '上傳失敗');
      }
    } catch (error: any) {
      console.error(`影片 ${file.name} 上傳失敗:`, error);
      alert(`影片 ${file.name} 上傳失敗: ` + (error.response?.data?.message || error.message || '網路錯誤'));
    }
  }
  e.target.value = ''; // 清空 input
}

// 通過 public_id 刪除 Cloudinary 資源（圖片或影片）
async function deleteCloudinaryById(publicId: string) {
  try {
    console.log('嘗試通過 public_id 刪除文章 Cloudinary 資源:', publicId);
    const response = await axios.post('/api/v1/admin/articles/delete-cloudinary-by-id', {
      public_id: publicId
    });
    
    console.log('刪除 API 響應:', response.data);
    
    if (!response.data.success) {
      throw new Error(response.data.message || '刪除失敗');
    }
    
    console.log('文章 Cloudinary 資源刪除成功');
    return response.data;
  } catch (error: any) {
    console.error('通過 public_id 刪除文章 Cloudinary 資源失敗:', error);
    console.error('錯誤詳情:', error.response?.data);
    throw error;
  }
}

function removeImage(idx: number) {
  // 使用 public_id 刪除 Cloudinary 上的圖片
  const publicIdToDelete = form.value.images_public_ids[idx];
  if (publicIdToDelete) {
    try {
      console.log('準備刪除文章圖片，public_id:', publicIdToDelete);
      // 調用刪除圖片 API
      deleteCloudinaryById(publicIdToDelete).then(() => {
        console.log('文章圖片刪除成功');
      }).catch((error) => {
        console.error('刪除 Cloudinary 圖片失敗:', error);
        // 即使刪除失敗也繼續，但提醒用戶
        alert('刪除圖片時發生錯誤：' + (error.message || '未知錯誤'));
      });
    } catch (error: any) {
      console.error('刪除圖片請求失敗:', error);
      alert('刪除圖片時發生錯誤：' + (error.message || '未知錯誤'));
    }
  }
  
  // 從陣列移除
  form.value.images.splice(idx, 1);
  form.value.images_public_ids.splice(idx, 1);
}

function removeVideo(idx: number) {
  // 使用 public_id 刪除 Cloudinary 上的影片
  const publicIdToDelete = form.value.videos_public_ids[idx];
  if (publicIdToDelete) {
    try {
      console.log('準備刪除文章影片，public_id:', publicIdToDelete);
      // 調用刪除影片 API
      deleteCloudinaryById(publicIdToDelete).then(() => {
        console.log('文章影片刪除成功');
      }).catch((error) => {
        console.error('刪除 Cloudinary 影片失敗:', error);
        // 即使刪除失敗也繼續，但提醒用戶
        alert('刪除影片時發生錯誤：' + (error.message || '未知錯誤'));
      });
    } catch (error: any) {
      console.error('刪除影片請求失敗:', error);
      alert('刪除影片時發生錯誤：' + (error.message || '未知錯誤'));
    }
  }
  
  // 從陣列移除
  form.value.videos.splice(idx, 1);
  form.value.videos_public_ids.splice(idx, 1);
}
function publishFB(id: number) {
  if (confirm('確定要發布到 Facebook？')) {
    axios.post(`/api/v1/admin/articles/${id}/publish-fb`).then(res => {
      alert(res.data.message || '已發布')
    }).catch(err => {
      alert(err.response?.data?.message || '發布失敗')
    })
  }
}

// 格式化日期
function formatDate(date: string) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('zh-TW')
}

// 取得狀態文字
function getStatusText(status: string) {
  const statusMap: { [key: string]: string } = {
    'draft': '草稿',
    'published': '已發布'
  }
  return statusMap[status] || status
}

// 取得狀態樣式
function getStatusClass(status: string) {
  const classMap: { [key: string]: string } = {
    'draft': 'bg-gray-100 text-gray-800',
    'published': 'bg-green-100 text-green-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

onMounted(fetchArticles)
</script>
<style scoped>
.input-field { @apply border rounded px-2 py-1 w-full; }
.btn-admin { @apply bg-amber-600 text-white font-semibold py-2 px-4 rounded hover:bg-amber-700 transition-colors; }
.btn-admin-sm { @apply bg-amber-600 text-white font-semibold py-1 px-3 text-sm rounded-md shadow hover:bg-amber-700 transition-colors whitespace-nowrap; }
.group:hover button[style] { display: block !important; }
.loader {
  border: 2px solid #f3f3f3;
  border-top: 2px solid #e53e3e;
  border-radius: 50%;
  width: 1em;
  height: 1em;
  animation: spin 0.8s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style> 