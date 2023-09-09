<template>
  <div class="row" style="width: 100%">
    <div v-for="filter in autoFilter" class="auto-tab col-md-4 col-sm-6 col-12">
      {{ filter.name }}
      <select v-model="selectedParams[filter.key]">
        <option value="All">
          All
        </option>
        <option v-for="value in filter.values" :value="value.idx">
          {{ value.value }}
        </option>
      </select>
    </div>
    <button @click="search">Search</button>
  </div>
</template>

<script>
import {ref, computed, toRaw} from 'vue';
import {useStore} from 'vuex';
export default {
  name: "AutoFilter",
  setup() {
    const store = useStore()

    const autoFilter = computed(() => store.state.search.autoFilter);
    const selectedParams = ref({});

    let autoParams = {};

    const search = () => {
      const selectedParamsArray = toRaw(selectedParams.value)

      if (0 !== Object.keys(selectedParamsArray).length) {

        let query = "?"

        for (const key in selectedParamsArray) {
          const value = selectedParamsArray[key];
          if ('All' !== value) {
            query += key + "=" + value + "&"
          }
        }

        query = query.slice(0, -1)

        console.log(query)

        autoParams['query'] = query
      }

      store.dispatch('search/getAutoCatalog', autoParams)
    }

    return {
      autoFilter,
      selectedParams,
      search
    }
  }
}
</script>

<style scoped>

</style>