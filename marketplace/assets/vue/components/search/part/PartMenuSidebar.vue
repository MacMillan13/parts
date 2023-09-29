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
import {removeSymbols} from "../../../services/symbolRemoverHelper";

const store = useStore()
const route = useRoute()
const router = useRouter()

const brand = route.params.brand
const model = route.params.model
const year = route.params.year
const code = route.params.code

const partCatalog = computed(() => store.state.search.partCatalog)

let autoParams = {};
autoParams.model = model
autoParams.brand = brand
autoParams.year = year
autoParams.code = code

store.dispatch('search/getPartCatalog', autoParams)

const getCatalogGroup = (catalog) => {

  const catalogName = removeSymbols(catalog.name)

  router.push('/' + brand + '/' + model + '/' + year + '/' + code + '/' + catalogName)
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
