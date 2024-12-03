<script setup>
import axios from "axios";
import { useRouter, useRoute } from "vue-router";
import { useUserStore } from "../stores/counter";
import { ref, onMounted, onUnmounted, computed } from "vue";
import {
  XIcon,
  MenuIcon,
  UserIcon,
  UsersIcon,
  PackageIcon,
  LibraryIcon,
  BookIcon,
  BookOpenIcon,
  ShoppingCartIcon,
  LogOutIcon,
  ChevronDownIcon,
} from "lucide-vue-next";

const store = useUserStore();
const router = useRouter();
const route = useRoute();
const token = store.token;

const customConfig = {
  Authorization: `Bearer ${token}`,
};

const isSidebarOpen = ref(window.innerWidth >= 1024);
const isLibraryMenuOpen = ref(false);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleLibraryMenu = () => {
  isLibraryMenuOpen.value = !isLibraryMenuOpen.value;
};

const handleResize = () => {
  isSidebarOpen.value = window.innerWidth >= 1024;
};

onMounted(() => {
  window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});

const isCurrentRoute = (name) => route.name === name;

const logout = async () => {
  try {
    const response = await axios.get("http://localhost:8000/api/logout", {
      headers: customConfig,
    });

    if (response.data.success) {
      store.reset();
      router.push("/");
    }
  } catch (error) {
    console.error("Logout error:", error);
  }
};
</script>

<template>
  <div class="flex">
    <!-- Toggle Button -->
    <button
      @click="toggleSidebar"
      class="fixed z-30 p-2 rounded-md shadow-md left-4 top-5 focus:outline-none focus:ring-2 focus:ring-gray-200 lg:hidden"
      :class="{ 'left-[14.5rem] z-50': isSidebarOpen }"
    >
      <MenuIcon v-if="!isSidebarOpen" class="w-6 h-6" />
      <XIcon v-else class="w-6 h-6" />
    </button>

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 z-40 h-screen w-72 transition-transform duration-300 ease-in-out lg:translate-x-0',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
      class="bg-gray-900 text-gray-300"
    >
      <!-- User Info -->
      <div class="flex items-center p-4 space-x-4 border-b border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-700 rounded-full">
          <UserIcon class="w-8 h-8" />
        </div>
        <div>
          <p class="text-sm truncate">{{ store.email }}</p>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-2">
          <!-- Pegawai -->
          <li>
            <router-link
              to="/pegawai"
              class="flex items-center p-2 rounded-md hover:bg-gray-800"
              :class="{ 'bg-gray-800': isCurrentRoute('pegawai') }"
            >
              <UsersIcon class="w-5 h-5 mr-3" />
              Pegawai
            </router-link>
          </li>

          <!-- Barang -->
          <li>
            <router-link
              to="/barang"
              class="flex items-center p-2 rounded-md hover:bg-gray-800"
              :class="{ 'bg-gray-800': isCurrentRoute('barang') }"
            >
              <PackageIcon class="w-5 h-5 mr-3" />
              Barang
            </router-link>
          </li>

          <!-- Library Menu -->
          <li>
            <button
              @click="toggleLibraryMenu"
              class="flex items-center justify-between w-full p-2 rounded-md hover:bg-gray-800"
            >
              <div class="flex items-center">
                <LibraryIcon class="w-5 h-5 mr-3" />
                Perpustakaan
              </div>
              <ChevronDownIcon
                class="w-4 h-4 transition-transform"
                :class="{ 'rotate-180': isLibraryMenuOpen }"
              />
            </button>
            <ul v-show="isLibraryMenuOpen" class="mt-2 space-y-1 px-2">
              <li>
                <router-link
                  to="/buku"
                  class="flex items-center p-2 pl-10 rounded-md hover:bg-gray-800"
                  :class="{ 'bg-gray-800': isCurrentRoute('buku') }"
                >
                  <BookIcon class="w-4 h-4 mr-3" />
                  Buku
                </router-link>
              </li>
              <li>
                <router-link
                  to="/peminjaman"
                  class="flex items-center p-2 pl-10 rounded-md hover:bg-gray-800"
                  :class="{ 'bg-gray-800': isCurrentRoute('peminjaman') }"
                >
                  <BookOpenIcon class="w-4 h-4 mr-3" />
                  Peminjaman
                </router-link>
              </li>
              <li>
                <router-link
                  to="/pembelian"
                  class="flex items-center p-2 pl-10 rounded-md hover:bg-gray-800"
                  :class="{ 'bg-gray-800': isCurrentRoute('pembelian') }"
                >
                  <ShoppingCartIcon class="w-4 h-4 mr-3" />
                  Pembelian
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Logout -->
          <li class="mt-auto">
            <button
              @click="logout"
              class="flex items-center p-2 text-red-400 rounded-md hover:bg-gray-800"
            >
              <LogOutIcon class="w-5 h-5 mr-3" />
              Logout
            </button>
          </li>
        </ul>
      </nav>
    </aside>
  </div>
</template>

<style scoped>
.rotate-180 {
  transform: rotate(180deg);
}
</style>
