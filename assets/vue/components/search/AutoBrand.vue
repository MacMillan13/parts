<template>
  <div class="row" v-if="null !== autoBrands">
    <div v-for="brand in autoBrands" class="auto-tab col-md-2 col-sm-4 col-6">
      <span>{{ brand.name }}</span>
    </div>
  </div>
</template>
<script>
import {computed} from 'vue';
import {useStore} from 'vuex';

export default {
  name: "FindCar",
  setup() {
    const autoBrands = computed(() => store.state.search.autoBrands);
    const store = useStore()

    store.dispatch('search/getCarBrands')

    return {
      autoBrands
    }
  }
}
</script>
<style scoped>
</style>
