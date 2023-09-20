<template>
  <div class="container">
    <div>
      <FindAuto />
    </div>
    <div v-if="step === 0">
      <AutoFilter />
    </div>
    <div>
      <div v-if="step === 1 && (autoList !== null || selectedAuto !== null)">
        <AutoList />
      </div>
      <div v-if="step === 2 && autoCatalog !== null">
        <PartCatalog />
      </div>
      <div v-if="step === 3 && autoCatalogGroup !== null">
        <PartCatalogGroup />
      </div>
      <div v-if="step === 4 && partSchema !== null">
        <PartSchema />
      </div>
      <div>
      </div>
    </div>
  </div>
</template>
<script>

import { useStore } from 'vuex'
import { ref, computed} from 'vue';
import AutoList from './search/auto/AutoList.vue';
import AutoCatalogGroup from './search/part/PartCatalogGroup.vue';
import PartSchema from './search/part/PartSchema.vue';
import FindAuto from './search/FindAuto';
import AutoFilter from './search/auto/AutoBrands.vue';
import AutoCatalog from './search/part/PartCatalog.vue';
import PartCatalog from "./search/part/PartCatalog.vue";
import PartCatalogGroup from "./search/part/PartCatalogGroup.vue";

export default {
  name: 'search',
  components: {
    PartCatalogGroup,
    PartCatalog,
    AutoList,
    AutoCatalog,
    AutoCatalogGroup,
    PartSchema,
    FindAuto,
    AutoFilter
  },
  setup() {
    const store = useStore()

    const step = computed(() => store.state.search.step);
    const autoList = computed(() => store.state.search.autoList);
    const selectedAuto = computed(() => store.state.search.autoList)
    const autoCatalog = computed(() => store.state.search.autoCatalog)
    const autoCatalogGroup = computed(() => store.state.search.autoCatalogGroup)
    const partSchema = computed(() => store.state.search.partSchema)
    const currentAuto = ref(null);

    const checkUrl = () => {

      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const brand = urlParams.get('brand');
      const catalog = urlParams.get('catalog');
      const auto = urlParams.get('auto');
      const group = urlParams.get('group');

      currentAuto.value = {
        catalogId: brand,
        id: auto
      };

      if (null !== brand && null !== auto && catalog == null && group == null) {

        const catalogParam = {
          id: catalog,
        };

        store.dispatch('search/getCatalog', {
          carId: catalogParam,
          toSet: true
        })
      }

      if (null !== brand && null !== auto && null !== catalog) {

        const catalogParam = {
          id: catalog,
        };

        store.dispatch('search/getCatalog', {
          carId: catalogParam,
          toSet: false
        })

        store.dispatch('search/getCatalogGroup', {})
      }

      if (null !== brand && null !== auto && null !== group) {

        const groupParam = {
          id: group,
        };

        store.dispatch('search/getPartGroup', groupParam)
      }
    };

    checkUrl();

    return {
      step,
      autoList,
      autoCatalog,
      autoCatalogGroup,
      selectedAuto,
      partSchema
    };
  }
};
</script>

<style>
h2, span, p {
  font-size: 16px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  padding: 10px;
}

img,
video,
iframe {
  max-inline-size: 100%;
  block-size: auto;
}
</style>
