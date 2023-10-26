<template>
  <div class="row" style="width: 100%">
    <div v-for="filter in autoFilter" class="auto-filter col-md-3 col-sm-4 col-12">
      <select class="custom-select" v-model="selectedParams[filter.key]">
        <option value="" disabled>{{ filter.name }}</option>
        <option value="All" selected>
          All
        </option>
        <option v-for="value in filter.values" :value="value.value">
          {{ value.value }}
        </option>
      </select>
    </div>
  </div>
</template>

<script setup>
import {ref, computed, toRaw, watch } from 'vue';
import { useRoute } from 'vue-router'
import {useStore} from 'vuex';

const store = useStore()
const route = useRoute()

const autoFilter = computed(() => store.state.search.autoFilter);
const selectedParams = ref({});

let autoParams = {};

watch(autoFilter, async () => {
  autoFilter.value.forEach(filter => {
    selectedParams.value[filter.key] = ''
  })
})

watch(selectedParams.value, async () => {
  search()
})

const getSearchFilterIdByName = () => {
  const selectedParamsObject = toRaw(selectedParams.value)
  console.log(222, selectedParamsObject)
  let searchParams = {}

  for (const property in selectedParamsObject) {
    if (selectedParamsObject[property].length > 0) {
      console.log(333)
      autoFilter.value.forEach(filter => {
        console.log(5555)
        if (filter.key === property) {
          filter.values.forEach(parameter => {
            if (parameter.value === selectedParamsObject[property]) {
              searchParams[property] = parameter.idx;
            }
          })
        }
      })
    }
  }

  return searchParams;
}

const search = () => {

  const searchParams = getSearchFilterIdByName()

  if (0 !== Object.keys(searchParams).length) {

    let query = "?"
    for (const key in searchParams) {
      const value = searchParams[key];
      if ('All' !== value && value.length > 0) {
        query += key + "=" + value + "&"
      }
    }

    query = query.slice(0, -1)

    autoParams.query = query

    sendRequest(autoParams)
  }
}

function sendRequest(autoParams) {
  autoParams.brand = route.params.brand
  autoParams.model = route.params.model

  store.dispatch('search/getAutoCatalog', autoParams)
}

sendRequest(autoParams);
</script>

<style scoped>
.row {
  margin-bottom: 8px;
}

#search-btn {
  height: 36px;
  border-radius: 3px;
  width: 100%;
}

.custom-select {
  width: 100%;
  cursor: pointer;
}

.auto-filter {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 8px 0;
}
</style>
