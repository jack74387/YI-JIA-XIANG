import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useAdminAuthStore } from '@/stores/adminAuth'
import HomeView from '../views/HomeView.vue'
import ProductDetailView from '../views/ProductDetailView.vue'
import BrandStoryView from '@/views/BrandStoryView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/products',
      name: 'products',
      component: () => import('../views/ProductsView.vue')
    },
    {
      path: '/products/:id',
      name: 'product-detail',
      component: () => import('../views/ProductDetailView.vue')
    },
    {
      path: '/product/:id',
      name: 'ProductDetail',
      component: ProductDetailView,
      props: true
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('../views/ContactView.vue')
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('../views/CartView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('../views/ProfileView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/orders',
      name: 'orders',
      component: () => import('../views/OrdersView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/gift',
      name: 'gift',
      component: () => import('../views/GiftServiceView.vue')
    },
    {
      path: '/food-trace',
      name: 'food-trace',
      component: () => import('../views/FoodTraceView.vue')
    },
    {
      path: '/faq',
      name: 'faq',
      component: () => import('../views/FAQView.vue')
    },
    {
      path: '/stores',
      name: 'stores',
      component: () => import('../views/StoreLocatorView.vue')
    },
    {
      path: '/group-order',
      name: 'group-order',
      component: () => import('../views/GroupOrderView.vue')
    },
    {
      path: '/member',
      name: 'member',
      component: () => import('../views/ProfileView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/line-friend',
      name: 'line-friend',
      component: () => import('../views/LineFriendView.vue')
    },
    {
      path: '/points',
      name: 'points',
      component: () => import('../views/PointsView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/coupon',
      name: 'coupon',
      component: () => import('../views/CouponView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: () => import('../views/CheckoutView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/store-locator',
      name: 'store-locator',
      component: () => import('../views/StoreLocatorView.vue')
    },
    {
      path: '/forgot-password',
      name: 'forgot-password',
      component: () => import('../views/ForgotPasswordView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/reset-password',
      name: 'reset-password',
      component: () => import('../views/ResetPasswordView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/orders/:id',
      name: 'order-detail',
      component: () => import('../views/OrderConfirmationView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/orders/:orderId/success',
      name: 'OrderSuccess',
      component: () => import('../views/OrderSuccessView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/member-center',
      name: 'member-center',
      component: () => import('../views/MemberCenterView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/admin/login',
      name: 'admin-login',
      component: () => import('../admin/AdminLogin.vue')
    },
    {
      path: '/admin',
      name: 'admin-dashboard',
      component: () => import('../admin/AdminDashboard.vue')
    },
    {
      path: '/admin/products',
      name: 'admin-products',
      component: () => import('../admin/AdminProducts.vue')
    },
    {
      path: '/admin/orders',
      name: 'admin-orders',
      component: () => import('../admin/AdminOrders.vue')
    },
    {
      path: '/admin/members',
      name: 'admin-members',
      component: () => import('../admin/AdminMembers.vue')
    },
    {
      path: '/admin/coupons',
      name: 'admin-coupons',
      component: () => import('../admin/AdminCoupons.vue')
    },
    {
      path: '/admin/admins',
      name: 'admin-admins',
      component: () => import('../admin/AdminAdmins.vue')
    },
    {
      path: '/admin/operation-logs',
      name: 'admin-operation-logs',
      component: () => import('../admin/AdminOperationLogs.vue')
    },
    {
      path: '/admin/stores',
      name: 'admin-stores',
      component: () => import('../admin/AdminStores.vue')
    },
    {
      path: '/admin/inventories',
      name: 'admin-inventories',
      component: () => import('../admin/AdminInventory.vue')
    },
    {
      path: '/admin/articles',
      name: 'admin-articles',
      component: () => import('../admin/AdminArticles.vue')
    },
    {
      path: '/articles',
      name: 'articles-list',
      component: () => import('../views/ArticlesListView.vue')
    },
    {
      path: '/articles/:id',
      name: 'article-detail',
      component: () => import('../views/ArticleDetailView.vue')
    },
    {
      path: '/artisan-craft',
      name: 'artisan-craft',
      component: () => import('../views/ArtisanCraftView.vue')
    },
    {
      path: '/shopping-notice',
      name: 'shopping-notice',
      component: () => import('../views/ShoppingNoticeView.vue')
    },
    {
      path: '/return-policy',
      name: 'return-policy',
      component: () => import('../views/ReturnPolicyView.vue')
    },
    {
      path: '/delivery-info',
      name: 'delivery-info',
      component: () => import('../views/DeliveryInfoView.vue')
    },
    {
      path: '/brand-story',
      name: 'BrandStory',
      component: BrandStoryView
    }
  ],
  scrollBehavior() {
    return { top: 0 }
  }
})

// 路由守衛
router.beforeEach(async (to, from, next) => {
  if (to.path.startsWith('/admin')) {
    const adminAuthStore = useAdminAuthStore()
    if (!adminAuthStore.isAuthenticated) {
      await adminAuthStore.initAuth()
    }
    if (!adminAuthStore.isAuthenticated && to.path !== '/admin/login') {
      next('/admin/login')
      return
    }
    next()
    return
  }
  // 前台驗證
  const authStore = useAuthStore()
  if (!authStore.isAuthenticated) {
    await authStore.initAuth()
  }
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
    return
  }
  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next('/')
    return
  }
  next()
})

export default router 