<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'
import { storeToRefs } from 'pinia'
import alertify from 'alertifyjs'

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
      alertify.success('Logged out successfully')
    }
  })
  .catch(error => {
    console.log('AJAX' + error)
    alertify.error('Logout failed')
  })
}
</script>

<template>
  <div class="d-flex">
    <nav class="sidebar bg-light">
      <div class="sidebar-sticky">
        <a class="navbar-brand" href="#">MyApp</a>
        <ul class="nav flex-column">
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
        <span class="navbar-text mt-3">
          Counter: {{ thecounter }}
        </span>
        <button class="btn btn-outline-danger mt-3" @click="logout">Logout</button>
      </div>
    </nav>
    <div class="content p-4">
      <router-view></router-view>
    </div>
  </div>
</template>

<style scoped>
.d-flex {
  display: flex;
}

.sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  padding: 20px;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.content {
  margin-left: 250px;
  width: calc(100% - 250px);
}

.nav-link {
  margin-bottom: 10px;
}

.navbar-text {
  display: block;
}
</style>