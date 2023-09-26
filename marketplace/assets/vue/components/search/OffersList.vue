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

  console.log(1111)

</script>

<style scoped>

</style>