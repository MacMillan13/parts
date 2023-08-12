<template>
  <div class="container">
    <div>
      <FindCar />
    </div>
    <div v-if="autoList === null && selectedAuto === null">
      <AutoBrand />
    </div>
    <div>
      <div v-if="step === 1 && (autoList !== null || selectedAuto !== null)">
        <AutoList />
      </div>
      <div v-if="step === 2 && autoCatalog !== null">
        <AutoCatalog />
      </div>
      <div v-if="step === 3 && autoCatalogGroup !== null">
        <AutoCatalogGroup />
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
import AutoList from './search/AutoList';
import AutoCatalogGroup from './search/AutoCatalogGroup';
import PartSchema from './search/PartSchema';
import FindCar from './search/FindCar';
import AutoBrand from './search/AutoBrand';
import AutoCatalog from './search/AutoCatalog';

export default {
  name: 'search',
  components: {
    AutoList,
    AutoCatalog,
    AutoCatalogGroup,
    PartSchema,
    FindCar,
    AutoBrand
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
          auto: catalogParam,
          toSet: true
        })
      }

      if (null !== brand && null !== auto && null !== catalog) {

        const catalogParam = {
          id: catalog,
        };

        store.dispatch('search/getCatalog', {
          auto: catalogParam,
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
