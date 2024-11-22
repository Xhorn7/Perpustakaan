import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import MenuView from '@/components/MenuView.vue'
import LoginView from '@/components/Login.vue'
import PegawaiView from '@/components/PegawaiView.vue'
import BarangView from '@/components/BarangView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/menu',
      name: 'menu',
      component: MenuView
    },
    {
      path: '/pegawai',
      name: 'pegawai',
      component: PegawaiView
    },
    {
      path: '/barang',
      name: 'barang',
      component: BarangView
    }
  ]
})

export default router
