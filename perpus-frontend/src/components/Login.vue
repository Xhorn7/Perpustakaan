<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter' // Ensure this import is correct

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

function login() {
  loading.value = true
  errorMessage.value = ''
  axios({
    url: 'http://localhost:8000/api/login',
    method: 'post',
    data: bodyParameters.value,
    headers: customConfig,
    withCredentials: true
  }).then(response => {
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
  })
  .catch(error => {
    console.log('AJAX' + error)
    errorMessage.value = 'An error occurred. Please try again later.'
  })
  .finally(() => {
    loading.value = false
  })
}
</script>

<template>
  <div class="login-container">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" v-model="username" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" v-model="password" required />
      </div>
      <button type="submit" :disabled="loading">
        <span v-if="loading">Loading...</span>
        <span v-else>Login</span>
      </button>
      <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
    </form>
  </div>
</template>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 2rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 0.75rem;
  border: none;
  border-radius: 4px;
  background-color: #007bff;
  color: white;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}

button:hover:not(:disabled) {
  background-color: #0056b3;
}

.error-message {
  color: red;
  margin-top: 1rem;
  text-align: center;
}
</style>