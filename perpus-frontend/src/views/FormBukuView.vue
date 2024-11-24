<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useUserStore } from '../stores/counter'

const isbn_readonly = ref(false)
const theroute = useRoute()
const therouter = useRouter()
const store = useUserStore()
const isbn = ref('')
const judul = ref('')
const pengarang = ref('')
const tahun = ref(2020)

const customConfig = {
  'Content-Type': 'application/json',
  'Authorization': 'Bearer ' + store.token
}

const thedata = computed(() => {
  return { 'isbn': isbn.value, 'judul': judul.value, 'pengarang': pengarang.value, 'tahun': tahun.value }
})

function save() {
  const store_or_update = theroute.params.theisbn !== undefined ? 'update' : 'store'
  axios({
    url: 'http://localhost/perpus/public/api/book/' + store_or_update,
    method: 'post',
    data: thedata.value,
    headers: customConfig
  }).then(response => {
    console.log(response.data) // only for development
    if (response.data.success === true) {
      alertify.alert('Information', 'Data has been saved!', function () { alertify.success('OK'); });
      therouter.push('/buku')
    }
  })
    .catch(error => {
      console.log('AJAX' + error)
    })
    .finally()
}

onMounted(() => {
  if (theroute.params.theisbn !== undefined) {
    isbn_readonly.value = true
    axios({
      url: 'http://localhost/perpus/public/api/book/show/' + theroute.params.theisbn,
      method: 'get',
      headers: customConfig
    }).then(response => {
      console.log(response.data) // only for development
      if (response.data.success === true) {
        isbn.value = response.data.data.isbn
        judul.value = response.data.data.judul
        pengarang.value = response.data.data.pengarang
        tahun.value = response.data.data.tahun
      }
    })
      .catch(error => {
        console.log('AJAX' + error)
      })
      .finally()
  }
})
</script>

<template>
  <input type="text" class="form-control" id="isbn" required v-model="isbn" :readonly="isbn_readonly">
  <input type="number" class="form-control" id="tahun" min="1980" max="2040" v-model="tahun">
</template>