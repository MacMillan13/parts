<template>
  <div class="row" style="width: 100%">
    <div v-for="filter in autoFilter" class="auto-filter col-md-3 col-sm-4 col-12">
      <select class="custom-select" v-model="selectedParams[filter.key]">
        <option value="" disabled>{{ filter.name }}</option>
        <option value="All" selected>
          All
        </option>
        <option v-for="value in filter.values" :value="value.idx">
          {{ value.value }}
        </option>
      </select>
    </div>
    <div v-if="showSearchButton" class="auto-filter col-md-3 col-sm-4 col-12">
      <button @click="search" id="search-btn" class="btn btn-primary active">Search</button>
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
const showSearchButton = ref(false);

let autoParams = {};


watch(autoFilter, async () => {

  autoFilter.value.forEach(filter => {
    selectedParams.value[filter.key] = ''
  })

  if (autoFilter.value.length > 0) {
    showSearchButton.value = true
  } else {
    showSearchButton.value = false
  }
})

const search = () => {
  const selectedParamsArray = toRaw(selectedParams.value)

  if (0 !== Object.keys(selectedParamsArray).length) {

    let query = "?"

    for (const key in selectedParamsArray) {
      const value = selectedParamsArray[key];
      if ('All' !== value && value.length > 0) {
        query += key + "=" + value + "&"
      }
    }


    query = query.slice(0, -1)

    autoParams['query'] = query
  }

  autoParams.brand = route.params.brand
  autoParams.model = route.params.model

  store.dispatch('search/getAutoCatalog', autoParams)
}
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
