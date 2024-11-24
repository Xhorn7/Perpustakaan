import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import { faUserSecret, faFolderPlus, faEye, faTrash, faSave, faSignOutAlt } from '@fortawesome/free-solid-svg-icons'
import { faUser } from '@fortawesome/free-regular-svg-icons'

library.add(faUserSecret, faFolderPlus, faEye, faTrash, faSave, faSignOutAlt)

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')