<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <PartMenuSidebar/>
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
<script setup>
import {useStore} from 'vuex'
import {computed, watch} from 'vue';
import PartMenuSidebar from "./PartMenuSidebar.vue";
import {useRoute} from 'vue-router'

const store = useStore()
const route = useRoute()

const partCatalogGroup = computed(() => store.state.search.partCatalogGroup)

const brand = route.params.brand
const model = route.params.model
const year = route.params.year
const code = route.params.code
const partCategory = route.params.partCategory

let autoParams = {};

autoParams.model = model
autoParams.brand = brand
autoParams.year = year
autoParams.code = code
autoParams.partCategory = partCategory

watch(route, () => {
  autoParams.model = route.params.model
  autoParams.brand = route.params.brand
  autoParams.year = route.params.year
  autoParams.code = route.params.code
  autoParams.partCategory = route.params.partCategory

  store.dispatch('search/getPartCatalogGroup', autoParams)
})

store.dispatch('search/getPartCatalogGroup', autoParams)

const getPartGroup = (partGroup) => {
  store.dispatch('search/getPartGroup', partGroup)
}
</script>
<style scoped>
</style>
