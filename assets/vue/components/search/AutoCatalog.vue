<template>
  <div>
    <ul>
      <li v-for="catalog in autoCatalog" @click="getCatalogGroup(catalog)">
        {{ catalog.name }}
      </li>
    </ul>
  </div>
</template>
<script>
import { useStore } from 'vuex'
import { computed} from 'vue';
export default {
  name: "AutoCatalog",
  setup() {
    const store = useStore()
    const autoCatalog = computed(() => store.state.search.autoCatalog)

    const getCatalogGroup = () => {
      store.dispatch('search/getCatalogGroup', {})
    }

    return {
      autoCatalog,
      getCatalogGroup
    }
  }
}
</script>
<style scoped>
</style>
