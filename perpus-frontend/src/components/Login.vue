<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter' // Ensure this import is correct
import { Dialog } from '@headlessui/vue'

axios.defaults.withCredentials = true;

const therouter = useRouter()
const auth = ref(null)
const username = ref('')
const password = ref('')
const store = useUserStore()
const loading = ref(false)
const errorMessage = ref('')

const customConfig = {
  'Content-Type': 'application/json',
};

const bodyParameters = computed(() => {
  return { 'email': username.value, 'password': password.value }
});

const login = async () => {
  loading.value = true
  try {
    const response = await axios({
      url: 'http://localhost:8000/api/login',
      method: 'post',
      data: bodyParameters.value,
      headers: customConfig,
      withCredentials: true,
    })
    auth.value = response.data
    console.log(response.data) // only for development
    if (auth.value.success === true) {
      store.email = username.value
      store.token = auth.value.data
      store.thecounter++
      console.log('Redirecting to /menu') // Debugging line
      therouter.push('/menu')
    } else {
      errorMessage.value = 'Login failed. Please check your credentials.'
    }
  } catch (error) {
    console.log('AJAX' + error)
    errorMessage.value = 'An error occurred. Please try again later.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-lg shadow-md">
      <h2 class="text-2xl font-bold text-center text-gray-900">Login</h2>
      <form @submit.prevent="login" class="space-y-6">
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input
            type="text"
            id="username"
            v-model="username"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            type="password"
            id="password"
            v-model="password"
            required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <div v-if="errorMessage" class="text-red-500 text-sm">{{ errorMessage }}</div>
        <div>
          <button
            type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="loading"
          >
            <span v-if="loading" class="loader"></span>
            <span v-else>Login</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.loader {
  border: 2px solid #f3f3f3;
  border-top: 2px solid #3498db;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>