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

if (undefined === route.params.vin) {
  getPartCatalogByAutoData();
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
