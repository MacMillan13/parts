<template>
  <Search />
  <div class="row block-margin">
    <div v-if="null !== autoBrands" v-for="brand in autoBrands" class="item-tab col-md-2 col-sm-4 col-6">
      <router-link :to="brand.catalogId">{{ brand.name }}</router-link>
    </div>
  </div>
</template>
<script setup>

import {computed} from 'vue';
import {useStore} from 'vuex';
import Search from "../Search.vue";

const store = useStore()
let autoBrands = computed(() => store.state.search.autoBrands);


store.dispatch('search/getAutoBrands')

</script>

<style scoped>
.row {
  margin: 24px 0;
}
</style>
