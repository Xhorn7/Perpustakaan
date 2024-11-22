<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'
import { storeToRefs, createPinia } from 'pinia'

const pini = createPinia()
const store = useUserStore()
const { thecounter } = storeToRefs(store)
const therouter = useRouter()
const customConfig = {
  'Authorization': 'Bearer ' + store.token
}

function logout() {
  axios({
    url: 'http://localhost/perpus/public/api/logout',
    method: 'get',
    headers: customConfig
  }).then(response => {
    console.log(response.data) // only for development
    if (response.data.success === true) {
      store.reset()
      therouter.push('/')
    }
  })
  .catch(error => {
    console.log('AJAX' + error)
  })
  .finally()
}
</script>

<template>
  <nav>
    <ul>
      <li><router-link to="/">Home</router-link></li>
      <li><router-link to="/about">About</router-link></li>
      <li><button @click="logout">Logout</button></li>
    </ul>
  </nav>
</template>

<style scoped>
nav {
  background-color: #f8f9fa;
  padding: 1rem;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline;
  margin-right: 1rem;
}

button {
  background: none;
  border: none;
  color: #007bff;
  cursor: pointer;
  text-decoration: underline;
}
</style>