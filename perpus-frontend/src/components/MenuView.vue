<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'
import { storeToRefs } from 'pinia'

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
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">MyApp</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/about">About</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/pegawai">Pegawai</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/barang">Barang</router-link>
          </li>
        </ul>
        <span class="navbar-text me-3">
          Counter: {{ thecounter }}
        </span>
        <button class="btn btn-outline-danger" @click="logout">Logout</button>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  margin-bottom: 20px;
}
</style>