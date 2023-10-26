<template>
  <div>
    wweee
  </div>
</template>

<script setup>
  import { useStore } from 'vuex'
  import { useRoute } from 'vue-router'

  const store = useStore()
  const route = useRoute()

  const partNumber = route.params.partNumber;

</script>

<style scoped>

</style>