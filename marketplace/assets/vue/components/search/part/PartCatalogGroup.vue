<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <ul>
          <li v-for="catalog in autoCatalog" @click="getCatalogGroup(catalog)">
            {{ catalog.name }}
          </li>
        </ul>
      </div>
      <div class="row col-sm-9">
        <div v-for="partGroup in partCatalogGroup" @click="getPartGroup(partGroup)"
             class="catalog-group-block col-sm-4 thumbnail">
          <div>
            <img :src="partGroup.img" alt="Part's group" class="embed-responsive-item">
          </div>
          {{ partGroup.name }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { useStore } from 'vuex'
import { computed} from 'vue';
export default {
  name: "PartCatalogGroup",
  setup() {
    const store = useStore()
    const autoCatalog = computed(() => store.state.search.autoCatalog)
    const partCatalogGroup = computed(() => store.state.search.autoCatalogGroup)

    const getCatalogGroup = (catalog) => {
      store.dispatch('search/getCatalogGroup', catalog)
    }

    const getPartGroup = (partGroup) => {
      store.dispatch('search/getPartGroup', partGroup)
    }

    return {
      autoCatalog,
      partCatalogGroup,
      getCatalogGroup,
      getPartGroup
    }
  }
}
</script>
<style scoped>
</style>
