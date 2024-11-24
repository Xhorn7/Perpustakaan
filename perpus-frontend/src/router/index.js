import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import MenuView from '@/views/MenuView.vue'
import LoginView from '@/views/Login.vue'
import PegawaiView from '@/views/PegawaiView.vue'
import BarangView from '@/views/BarangView.vue'
import BukuView from '@/views/BukuView.vue'
import FormBukuView from '@/views/FormBukuView.vue'

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
    },
    {
      path: '/buku',
      name: 'buku',
      component: BukuView
    },
    {
      path: '/bukuview/:theisbn',
      name: 'bukuview',
      component: FormBukuView
    }
  ]
})

export default router