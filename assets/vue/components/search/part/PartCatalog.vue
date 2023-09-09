<template>
  <div>
    <ul>
      <li v-if="partCatalog !== null" v-for="catalog in partCatalog" @click="getCatalogGroup(catalog)">
        {{ catalog.name }}
      </li>
    </ul>
  </div>
</template>
<script>
import { useStore } from 'vuex'
import { computed} from 'vue'
import { useRoute } from 'vue-router'

export default {
  name: "PartCatalog",
  setup() {
    const store = useStore()
    const route = useRoute()

    const brand = route.params.brand
    const model = route.params.model
    const year = route.params.year
    const modification = route.params.modification

    const partCatalog = computed(() => store.state.search.partCatalog)

    let autoParams = {};
    autoParams.model = model
    autoParams.brand = brand
    autoParams.year = year
    autoParams.modification = modification

    store.dispatch('search/getPartCatalog', autoParams)

    const getCatalogGroup = () => {
      store.dispatch('search/getCatalogGroup', {})
    }

    return {
      partCatalog,
      getCatalogGroup
    }
  }
}
</script>
<style scoped>
</style>
