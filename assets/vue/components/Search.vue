<template>
  <div class="container">
    <FindAuto />
    <AutoBrands v-if="step === 0" />
    <AutoList v-if="step === 1 && (autoList !== null || selectedAuto !== null)" />
  </div>
</template>
<script>

import FindAuto from "./search/FindAuto.vue";
import AutoBrands from "./search/auto/AutoBrands.vue";
import AutoList from './search/auto/AutoList.vue';
import { computed } from 'vue';
import { useStore } from 'vuex'

export default {
  name: 'Search',
  components: {
    FindAuto,
    AutoBrands,
    AutoList
  },
  setup() {
    const store = useStore()
    const step = computed(() => store.state.search.step);
    const autoList = computed(() => store.state.search.autoList);
    const selectedAuto = computed(() => store.state.search.autoList)

    return {
      step,
      autoList,
      selectedAuto
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
