<template>
  <div>
    <PartMenuSidebar />
  </div>
</template>

<script setup>
import {useStore} from 'vuex'
import {computed} from 'vue';
import {useRoute} from 'vue-router'
import {vinCodeLength} from "../../../../constants/auto"
import PartMenuSidebar from "../../part/PartMenuSidebar.vue";

const route = useRoute()
const store = useStore()

const autoByVin = computed(() => store.state.search.autoByVin)

const vinCode = route.params.vin

// if ((autoByVin.value === null && vinCode.length === vinCodeLength)
//     || (autoByVin.value !== null && autoByVin.value.vin !== vinCode)) {

  store.dispatch('search/getPartCatalogByVin', vinCode)
// }

</script>

<style scoped>

</style>