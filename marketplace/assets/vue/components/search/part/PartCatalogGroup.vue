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
import {useRoute, useRouter} from 'vue-router'

const store = useStore()
const route = useRoute()
const router = useRouter()

const partCatalogGroup = computed(() => store.state.search.partCatalogGroup)

let params = {};

watch(route, () => {
  getCatalogGroup();
})

getCatalogGroup();

function getCatalogGroup() {
  if (undefined === route.params.vin) {
    getPartCatalogGroup()
  } else {
    getPartCatalogGroupByVin()
  }
}

function getPartCatalogGroupByVin() {

}

function getPartCatalogGroup() {

  params.model = route.params.model
  params.brand = route.params.brand
  params.year = route.params.year
  params.code = route.params.code
  params.partCategory = route.params.partCategory

  store.dispatch('search/getPartCatalogGroup', params)
}


const getPartGroup = (partGroup) => {
  router.push('/' + params.brand + '/' + params.model + '/' + params.year + '/' + params.code
      + '/' + params.partCategory + '/' + partGroup.code)
}
</script>
<style scoped>
</style>
