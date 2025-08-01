<template>
  <div class="flex min-h-screen">
    <AdminSidebar />
    <main class="flex-1 p-6 bg-gray-50">
      <h1 class="text-2xl font-bold mb-4">文章管理</h1>
      <button class="btn-admin-sm mb-4" @click="openCreate">新增文章</button>
      <table class="w-full border mb-6">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-2">ID</th>
            <th class="p-2">標題</th>
            <th class="p-2">狀態</th>
            <th class="p-2">發布時間</th>
            <th class="p-2 text-right">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="a in articles" :key="a.id">
            <td class="p-2">{{ a.id }}</td>
            <td class="p-2">{{ a.title }}</td>
            <td class="p-2">{{ a.status }}</td>
            <td class="p-2">{{ a.published_at ? a.published_at.split('T')[0] : '' }}</td>
            <td class="p-2 text-right">
              <div class="inline-flex gap-2">
                <button class="btn-admin-sm" @click="openEdit(a)">編輯</button>
                <button class="btn-admin-sm bg-red-600 hover:bg-red-700" @click="del(a.id)">刪除</button>
                <button v-if="a.status==='published'" class="btn-admin-sm bg-blue-600 hover:bg-blue-700" @click="publishFB(a.id)">發布到FB</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50" @click="closeModal">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl relative" @click.stop>
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
  videos: [] as string[],
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
  form.value = { id: null, title: '', content: '', images: [], videos: [], status: 'draft', published_at: '' }
}
function openEdit(a: any) {
  editing.value = true
  showModal.value = true
  form.value = { ...a, images: a.images||[], videos: a.videos||[], published_at: a.published_at ? a.published_at.replace(' ', 'T') : '' }
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
  if (confirm('確定刪除？')) axios.delete(`/api/v1/admin/articles/${id}`).then(fetchArticles)
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
        form.value.images.push(res.data.url);
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
        form.value.videos.push(res.data.url);
      } else {
        throw new Error(res.data.message || '上傳失敗');
      }
    } catch (error) {
      console.error(`影片 ${file.name} 上傳失敗:`, error);
      alert(`影片 ${file.name} 上傳失敗: ` + (error.response?.data?.message || error.message || '網路錯誤'));
    }
  }
  e.target.value = ''; // 清空 input
}

function removeImage(idx: number) {
  form.value.images.splice(idx, 1);
}

function removeVideo(idx: number) {
  form.value.videos.splice(idx, 1);
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

onMounted(fetchArticles)
</script>
<style scoped>
.input-field { @apply border rounded px-2 py-1 w-full; }
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