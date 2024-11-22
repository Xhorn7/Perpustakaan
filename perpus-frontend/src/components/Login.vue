<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const therouter = useRouter()
const auth = ref(null)
const username = ref('')
const password = ref('')
const store = useUserStore()

const customConfig = {
  'Content-Type': 'application/json',
};

const bodyParameters = computed(() => {
  return { 'email': username.value, 'password': password.value }
});

function login() {
  axios({
    url: 'http://localhost:8000/api/login',
    method: 'post',
    data: bodyParameters.value,
    headers: customConfig
  }).then(response => {
    auth.value = response.data
    console.log(response.data) // only for development
    if (auth.value.success === true) {
      store.email = username.value
      store.token = auth.value.data
      store.thecounter++
      therouter.push('/menu')
    }
  })
  .catch(error => {
    console.log('AJAX' + error)
  })
  .finally()
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
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f9f9f9;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
}

input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
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
}

button:hover {
  background-color: #0056b3;
}
</style>