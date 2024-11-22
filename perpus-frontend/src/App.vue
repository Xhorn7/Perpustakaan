<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import { ref, computed } from 'vue'
import FooterView from './components/FooterView.vue'
import MenuView from './components/MenuView.vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from './stores/counter'

axios.defaults.withCredentials = true;

const theroute = useRoute()
const isnotlogin = computed(() => {
  return theroute.name != 'login' ? true : false
})

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
    headers: customConfig,
    withCredentials: true
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
  <div>
      <MenuView v-if="isnotlogin" />
      <div class="row" style="margin-top: 65px">
          <router-view></router-view>
      </div>
      <FooterView v-if="isnotlogin" />
  </div>
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
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 1rem;
  }
}

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
