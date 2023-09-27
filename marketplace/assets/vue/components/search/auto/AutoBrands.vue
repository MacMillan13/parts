<template>
  <FindAuto/>

  <div class="row">
    <div v-if="null !== autoBrands" v-for="brand in autoBrands" class="auto-tab col-md-2 col-sm-4 col-6">
      <router-link :to="brand.carId">{{ brand.name }}</router-link>
    </div>
  </div>
</template>
<script setup>

import {computed} from 'vue';
import {useStore} from 'vuex';
import FindAuto from "../FindAuto.vue";

const store = useStore()
const autoBrands = computed(() => store.state.search.autoBrands);

store.dispatch('search/getAutoBrands')

</script>

<style scoped>
.row {
  margin: 24px 0;
}
</style>
