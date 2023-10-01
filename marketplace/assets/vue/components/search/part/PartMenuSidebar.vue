<template>
  <div>
    <ul>
      <li v-if="partCatalog !== null" v-for="catalog in partCatalog" @click="getCatalogGroup(catalog)">
        {{ catalog.name }}
      </li>
    </ul>
  </div>
</template>
<script setup>
import {useStore} from 'vuex'
import {computed} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import {vinCodeLength} from "../../../constants/auto";

const store = useStore()
const route = useRoute()
const router = useRouter()

let link = '';

const partCatalog = computed(() => store.state.search.partCatalog)

const getPartCatalogByAutoData = () => {
  let autoParams = {};

  autoParams.brand = route.params.brand
  autoParams.model = route.params.model
  autoParams.year = route.params.year
  autoParams.code = route.params.code

  store.dispatch('search/getPartCatalog', autoParams)
}

const getPartCatalogByVin = () => {

  const autoByVin = computed(() => store.state.search.autoByVin)

  const vinCode = route.params.vin

  if ((autoByVin.value === null && vinCode.length === vinCodeLength)
      || (autoByVin.value !== null && autoByVin.value.vin !== vinCode)) {

    store.dispatch('search/getPartCatalogByVin', vinCode)
  }
}

if (undefined === route.params.vin) {
  getPartCatalogByAutoData();

} else {
  getPartCatalogByVin();
}

const getCatalogGroup = (catalog) => {
  if (undefined === route.params.vin) {
    link = '/' + route.params.brand + '/' + route.params.model + '/' + route.params.year
        + '/' + route.params.code + '/' + catalog.code
  } else {
    link = '/vin/' + route.params.vin + '/' + catalog.code
  }

  router.push(link)
}

</script>
<style scoped>
li {
  cursor: pointer;
  width: 270px;
  padding-left: 15px;
}

li:hover {
  background: #E0E0E0;
  border-radius: 2px;
}
</style>
