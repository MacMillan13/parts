<template>
  <div class="row">
    <div v-if="0 === step && null !== autoBrands" v-for="brand in autoBrands" class="item-tab col-md-2 col-sm-4 col-6">
      <router-link :to="brand.carId">{{ brand.name }}</router-link>
    </div>
    <div v-if="1 === step && null !== models" v-for="model in models" class="item-tab col-md-2 col-sm-4 col-6">
      <span @click="getAutoCatalog(model)">{{ model.name }}</span>
    </div>
    <div v-if="2 === step && null !== autoFilter" class="row" style="width: 100%">
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
      <div>
        <AutoList />
      </div>
    </div>
  </div>
</template>
<script>
import {ref, computed, toRaw} from 'vue';
import {useStore} from 'vuex';
import AutoList from './auto/AutoList.vue';
import { useRouter, useRoute } from 'vue-router'

export default {
  name: "OldAutoFilter",
  components: {
    AutoList
  },
  setup() {
    const router = useRouter()
    const route = useRoute()

    const step = ref(0);
    const brandName = ref(null);
    const selectedParams = ref({});

    let autoParams = {};

    const store = useStore()

    const models = computed(() => store.state.search.autoModel);
    const autoBrands = computed(() => store.state.search.autoBrands);
    const autoCatalog = computed(() => store.state.search.autoCatalog);
    const autoFilter = computed(() => store.state.search.autoFilter);
    const autoList = computed(() => store.state.search.autoList);

    store.dispatch('search/getAutoBrands')

    const getModels = (brand) => {
      brandName.value = brand
      router.push({
        name: 'Models',
        params: {
          'brand': brand
        },
      })
    }

    const getAutoCatalog = (model) => {
      autoParams.carId = model.id
      autoParams.brand = brandName.value
      store.dispatch('search/getAutoCatalog', autoParams)
      step.value = 2
    }

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
      autoBrands,
      models,
      step,
      autoCatalog,
      autoFilter,
      autoList,
      selectedParams,
      search,
      getModels,
      getAutoCatalog
    }
  }
}
</script>
<style scoped>
</style>
