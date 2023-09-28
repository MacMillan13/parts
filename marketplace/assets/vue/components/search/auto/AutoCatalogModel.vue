<template>
  <div class="block-margin">
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

  let autoParams = {};
  autoParams.model = model
  autoParams.brand = brand

  store.dispatch('search/getAutoCatalogModel', autoParams)

</script>

<style scoped>

</style>