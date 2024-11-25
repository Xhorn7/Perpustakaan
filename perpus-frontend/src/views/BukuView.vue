<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/counter'
import { Dialog } from '@headlessui/vue'
import alertify from 'alertifyjs'

const books = ref([])
const store = useUserStore()
const therouter = useRouter()
const customConfig = store.token
  ? { Authorization: 'Bearer ' + store.token }
  : {}

const showDeleteDialog = ref(false)
const bookToDelete = ref(null)

function refreshdata() {
  axios
    .get('http://localhost:8000/api/book/buku', { headers: customConfig })
    .then(response => {
      books.value = response.data.data || [] // Handle jika data kosong
      console.log('Books fetched:', books.value)
    })
    .catch(error => {
      console.error('Error fetching data:', error)
    })
}

onMounted(() => {
  refreshdata()
})

function delete_dialog(isbn) {
  bookToDelete.value = isbn
  showDeleteDialog.value = true
}

function hapus() {
  if (!bookToDelete.value) return
  console.log(bookToDelete.value)

  axios({
    url: `http://localhost:8000/api/book/delete/${bookToDelete.value}`,
    method: 'delete', // Change to appropriate method if needed
    headers: customConfig,
  })
    .then(response => {
      if (response.data.success) {
        alertify.alert('Information', 'Data has been deleted!', function () {
          alertify.success('OK')
        })
        refreshdata()
        showDeleteDialog.value = false
      } else {
        alertify.error('Failed to delete the book.')
      }
    })
    .catch(error => {
      console.error('Error deleting book:', error)
    })
}
</script>

<template>
  <div class="container mx-auto mt-4 ml-72 pr-80">
    <div class="flex justify-between items-center mb-3">
      <h2 class="text-2xl font-bold">Book List</h2>
      <router-link to="/formbuku">
        <button type="button" class="btn btn-primary">
          <font-awesome-icon :icon="['fas', 'folder-plus']" /> 
          Add Book
        </button>
      </router-link>
    </div>
    <table class="min-w-full bg-white border border-gray-200">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b">#</th>
          <th class="py-2 px-4 border-b">ISBN</th>
          <th class="py-2 px-4 border-b">Title</th>
          <th class="py-2 px-4 border-b">Author</th>
          <th class="py-2 px-4 border-b">Year</th>
          <th class="py-2 px-4 border-b">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in books" :key="item.isbn" class="hover:bg-gray-100">
          <td class="py-2 px-4 border-b text-right">{{ index + 1 }}</td>
          <td class="py-2 px-4 border-b">{{ item.isbn }}</td>
          <td class="py-2 px-4 border-b">{{ item.judul }}</td>
          <td class="py-2 px-4 border-b">{{ item.pengarang }}</td>
          <td class="py-2 px-4 border-b text-center">{{ item.tahun }}</td>
          <td class="py-2 px-4 border-b">
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

    <Dialog :open="showDeleteDialog" @close="showDeleteDialog = false">
      <Dialog.Overlay class="fixed inset-0 bg-black opacity-30" />
      <Dialog.Panel class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
          <Dialog.Title class="text-lg font-bold">Are you absolutely sure?</Dialog.Title>
          <Dialog.Description class="mt-2 text-sm text-gray-500">
            This action cannot be undone. This will permanently delete the book and remove its data from our servers.
          </Dialog.Description>
          <div class="mt-4 flex justify-end space-x-2">
            <button @click="showDeleteDialog = false" class="btn btn-secondary">Cancel</button>
            <button @click="hapus" class="btn btn-danger">Continue</button>
          </div>
        </div>
      </Dialog.Panel>
    </Dialog>
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