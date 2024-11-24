<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'
import alertify from 'alertifyjs'

const books = ref([])
const store = useUserStore()
const therouter = useRouter()
const customConfig = {
  'Authorization': 'Bearer ' + store.token
}

function refreshdata() {
  axios({
    url: 'http://localhost/perpus/public/api/book/buku',
    method: 'get',
    headers: customConfig
  }).then(response => {
    console.log(response.data) // only for development
    if (response.data.success === true) {
      books.value = response.data.data
    }
  })
  .catch(error => {
    console.log('AJAX' + error)
  })
}

onMounted(() => {
  refreshdata()
})

function delete_dialog(id) {
  alertify.confirm('Confirmation', 'Are you sure to delete this data?', 
    function() { 
      hapus(id)
    }, function() { 
      alertify.error('Cancel')
    })
}

function hapus(id) {
  axios({
    url: 'http://localhost/perpus/public/api/book/delete/' + id,
    method: 'get',
    headers: customConfig
  }).then(response => {
    if (response.data.success === true) {
      alertify.alert('Information', 'Data has been deleted!', function() { 
        alertify.success('OK');  
      });
      refreshdata()
    }
  })
  .catch(error => {
    console.log('AJAX' + error)
  })
}
</script>

<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Book List</h2>
      <router-link to="/formbuku">
        <button type="button" class="btn btn-primary">
          <font-awesome-icon :icon="['fas', 'folder-plus']" /> 
          Add Book
        </button>
      </router-link>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>ISBN</th>
          <th>Title</th>
          <th>Author</th>
          <th>Year</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in books" :key="item.isbn">
          <td class="text-end">{{ index + 1 }}</td>
          <td>{{ item.isbn }}</td>
          <td>{{ item.judul }}</td>
          <td>{{ item.pengarang }}</td>
          <td class="text-center">{{ item.tahun }}</td>
          <td>
            <router-link :to="{ name: 'bukuview', params: { 'theisbn': item.isbn } }">
              <button type="button" class="btn btn-outline-success btn-sm">
                <font-awesome-icon :icon="['fas', 'eye']" /> View
              </button>
            </router-link>
            <button type="button" @click="delete_dialog(item.isbn)" class="btn btn-outline-danger btn-sm">
              <font-awesome-icon :icon="['fas', 'trash']" /> Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.container {
  margin-top: 20px;
}

.table {
  margin-top: 20px;
}

.btn {
  margin-right: 5px;
}
</style>