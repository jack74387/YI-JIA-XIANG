import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

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
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue')
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
      component: () => import('../views/ForgotPasswordView.vue')
    },
    {
      path: '/order-detail/:id',
      name: 'order-detail',
      component: () => import('../views/OrderDetailView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/member-center',
      name: 'member-center',
      component: () => import('../views/MemberCenterView.vue'),
      meta: { requiresAuth: true }
    }
  ]
})

export default router 