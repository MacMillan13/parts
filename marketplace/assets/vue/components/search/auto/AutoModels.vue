<template>
  <div class="row">
    <div v-for="model in models" class="auto-tab col-md-2 col-sm-4 col-6">
      <router-link :to="'/' + brand + '/' + model.name.toLowerCase()" >{{ model.name }}</router-link>
    </div>
  </div>
</template>
<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import {useStore} from 'vuex';
export default {
  name: "AutoModels",
  setup() {
    const store = useStore()

    const models = computed(() => store.state.search.autoModel);

    const route = useRoute()

    const brand = route.params.brand

    const getModels = (autoModel) => {
      store.dispatch('search/getAutoModels', autoModel)
    }

    getModels(brand)

    return {
      models,
      brand
    }
  }
}
</script>
<style scoped>
</style>
