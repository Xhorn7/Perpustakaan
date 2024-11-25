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
const therouter = useRouter();
const theroute = useRoute();
const customConfig = {
    Authorization: "Bearer " + store.token,
};

const isOpen = ref(window.innerWidth >= 1024);
const isLibraryOpen = ref(false);

const toggleSidebar = () => {
    isOpen.value = !isOpen.value;
};

const toggleLibraryMenu = () => {
    isLibraryOpen.value = !isLibraryOpen.value;
};

const handleResize = () => {
    isOpen.value = window.innerWidth >= 1024;
};

onMounted(() => {
    window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

const ismenu = computed(() => {
    return theroute.name == "menu" ? true : false;
});

const currentRoute = computed(() => theroute.name);

const logout = () => {
    axios({
        url: "http://localhost:8000/api/logout",
        method: "get",
        headers: customConfig,
    })
        .then((response) => {
            if (response.data.success === true) {
                store.reset();
                therouter.push("/");
            }
        })
        .catch((error) => {
            console.log("AJAX" + error);
        });
};
</script>

<template>
    <div class="flex">
        <button
            @click="toggleSidebar"
            class="fixed z-30 p-2 rounded-md shadow-md left-4 top-5 focus:outline-none focus:ring-2 focus:ring-gray-200 lg:hidden"
            :class="{ 'left-[14.5rem] z-50': isOpen }"
        >
            <MenuIcon v-if="!isOpen" class="z-40 w-6 h-6" />
            <XIcon v-else class="w-6 h-6" />
        </button>

        <div
            :class="[
                'fixed top-0 left-0 z-40 h-screen w-72 transition-transform duration-300 ease-in-out lg:translate-x-0',
                isOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <aside
                class="flex flex-col w-full h-full text-gray-300 bg-gray-900"
            >
                <div
                    class="flex items-center p-4 space-x-4 border-b border-gray-700"
                >
                    <div
                        class="flex items-center justify-center w-12 h-12 bg-gray-700 rounded-full"
                    >
                        <UserIcon class="w-8 h-8" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-300 truncate">
                            {{ store.email }}
                        </p>
                    </div>
                </div>

                <nav class="flex-1 p-4 overflow-y-auto">
                    <ul class="space-y-2">
                        <li>
                            <router-link
                                to="/pegawai"
                                class="flex items-center p-2 rounded-md hover:bg-gray-800"
                                :class="{
                                    'bg-gray-800': currentRoute === 'pegawai',
                                }"
                            >
                                <UsersIcon class="w-5 h-5 mr-3" />
                                Pegawai
                            </router-link>
                        </li>
                        <li>
                            <router-link
                                to="/barang"
                                class="flex items-center p-2 rounded-md hover:bg-gray-800"
                                :class="{
                                    'bg-gray-800': currentRoute === 'barang',
                                }"
                            >
                                <PackageIcon class="w-5 h-5 mr-3" />
                                Barang
                            </router-link>
                        </li>

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
                                    :class="{ 'rotate-180': isLibraryOpen }"
                                />
                            </button>

                            <ul
                                v-show="isLibraryOpen"
                                class="px-2 mt-2 space-y-1"
                            >
                                <li>
                                    <router-link
                                        to="/buku"
                                        class="flex items-center p-2 pl-10 rounded-md hover:bg-gray-800"
                                        :class="{
                                            'bg-gray-800':
                                                currentRoute === 'buku',
                                        }"
                                    >
                                        <BookIcon class="w-4 h-4 mr-3" />
                                        Buku
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        to="/peminjaman"
                                        class="flex items-center p-2 pl-10 rounded-md hover:bg-gray-800"
                                        :class="{
                                            'bg-gray-800':
                                                currentRoute === 'peminjaman',
                                        }"
                                    >
                                        <BookOpenIcon class="w-4 h-4 mr-3" />
                                        Peminjaman
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                        to="/pembelian"
                                        class="flex items-center p-2 pl-10 rounded-md hover:bg-gray-800"
                                        :class="{
                                            'bg-gray-800':
                                                currentRoute === 'pembelian',
                                        }"
                                    >
                                        <ShoppingCartIcon
                                            class="w-4 h-4 mr-3"
                                        />
                                        Pembelian
                                    </router-link>
                                </li>
                            </ul>
                        </li>

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
    </div>
</template>

<style scoped>
.rotate-180 {
  transform: rotate(180deg);
}
</style>