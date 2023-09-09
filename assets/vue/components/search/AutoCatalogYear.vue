<template>
  <div>
    <AutoFilter />
    <AutoList />
  </div>
</template>

<script setup>
  import { useRoute } from 'vue-router'
  import { useStore } from 'vuex';
  import AutoFilter from "./AutoFilter.vue";
  import AutoList from "./AutoList.vue";

  const store = useStore()

  const route = useRoute()

  const brand = route.params.brand
  const model = route.params.model
  const year = route.params.year

  let autoParams = {};
  autoParams.model = model
  autoParams.brand = brand
  autoParams.year = year

  store.dispatch('search/getAutoCatalogYear', autoParams)

</script>

<style scoped>

</style>