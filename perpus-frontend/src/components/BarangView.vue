<script setup>
import { useUserStore } from '../stores/counter'

const store = useUserStore()

function increase() {
  store.thecounter++
}
</script>

<template>
  <div class="barang-view">
    <button type="button" @click="increase" class="btn btn-danger">
      COUNTER IN BARANG
    </button>
    <h3>Click to increase THE COUNTER</h3>
  </div>
</template>

<style scoped>
.barang-view {
  text-align: center;
  margin-top: 20px;
}

.btn {
  margin-bottom: 10px;
}
</style>