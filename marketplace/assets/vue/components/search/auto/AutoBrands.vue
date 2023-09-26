<template>
  <div class="row">
    <div v-if="null !== autoBrands" v-for="brand in autoBrands" class="auto-tab col-md-2 col-sm-4 col-6">
      <router-link :to="brand.carId">{{ brand.name }}</router-link>
    </div>
  </div>
</template>
<script>
import {computed } from 'vue';
import {useStore} from 'vuex';
import AutoList from './AutoList.vue';

export default {
  name: "AutoBrands",
  components: {
    AutoList
  },
  setup() {
    const store = useStore()
    const autoBrands = computed(() => store.state.search.autoBrands);

    store.dispatch('search/getAutoBrands')

    return {
      autoBrands
    }
  }
}
</script>
<style scoped>
</style>
