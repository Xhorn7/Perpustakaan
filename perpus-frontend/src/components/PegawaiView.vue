<script setup>
import { useUserStore } from '../stores/counter'

const store = useUserStore()

function increase() {
  store.thecounter++
}
</script>

<template>
  <div class="pegawai-view">
    <button type="button" @click="increase" class="btn btn-primary">
      COUNTER IN PEGAWAI
    </button>
    <h3>Click to increase THE COUNTER</h3>
  </div>
</template>

<style scoped>
.pegawai-view {
  text-align: center;
  margin-top: 20px;
}

.btn {
  margin-bottom: 10px;
}
</style>