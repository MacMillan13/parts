<template>
  <FindAuto />
  <div class="row block-margin">
    <div v-for="model in models" class="item-tab col-md-2 col-sm-4 col-6">
      <router-link :to="'/' + brand + '/' + model.code" >{{ model.name }}</router-link>
    </div>
  </div>
</template>
<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import {useStore} from 'vuex';
import FindAuto from "../Search.vue";
export default {
  name: "AutoModels",
  components: {FindAuto},
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
