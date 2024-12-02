import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faFolderPlus, faEye, faTrash } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import { VueAwesomePaginate } from 'vue-awesome-paginate';
import "../node_modules/vue-awesome-paginate/dist/style.css";

library.add(faFolderPlus, faEye, faTrash)

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.component('font-awesome-icon', FontAwesomeIcon)

app.component(VueAwesomePaginate);

app.mount('#app')