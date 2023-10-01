<template>
  <div>
    <table class="table">
      <thead>
      <tr>
        <th scope="col">Brand</th>
        <th scope="col">Model designation</th>
        <th scope="col">Year</th>
        <th scope="col">Region</th>
        <th scope="col">Steering</th>
        <th scope="col">Transmission type</th>
        <th scope="col">Sunroof</th>
        <th scope="col">Navigation</th>
        <th scope="col">Electronic Stability Control (VSA)</th>
        <th scope="col">Door count</th>
        <th scope="col">ABS</th>
      </tr>
      </thead>
      <tbody>
      <tr v-if="autoList !== null" v-for="auto in autoList" @click="getCatalog(auto)">
        <td>{{ auto.brand }}</td>
        <td>{{ auto.name }}</td>
        <td>{{ auto.year }}</td>
        <td>{{ auto.sales_region }}</td>
        <td>{{ auto.steering }}</td>
        <td>{{ auto.trans_type }}</td>
        <td>{{ auto.sunroof }}</td>
        <td>{{ auto.navigation }}</td>
        <td>{{ auto.vsa }}</td>
        <td>{{ auto.door_count }}</td>
        <td>{{ auto.abs }}</td>
      </tr>
      <tr v-if="autoByVin !== null">
        <td>{{ autoByVin.brand }}</td>
        <td>{{ autoByVin.name }}</td>
        <td>{{ autoByVin.year }}</td>
        <td>{{ autoByVin.sales_region }}</td>
        <td>{{ autoByVin.steering }}</td>
        <td>{{ autoByVin.trans_type }}</td>
        <td>{{ autoByVin.sunroof }}</td>
        <td>{{ autoByVin.navigation }}</td>
        <td>{{ autoByVin.vsa }}</td>
        <td>{{ autoByVin.door_count }}</td>
        <td>{{ autoByVin.abs }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
import {computed} from 'vue';
import {useStore} from 'vuex';
import {useRoute, useRouter} from 'vue-router'

const store = useStore()
const route = useRoute()
const router = useRouter()

const autoByVin = computed(() => store.state.search.autoByVin);
const autoList = computed(() => store.state.search.autoList);

const getCatalog = async (auto) => {
  router.push('/' + auto.catalogId.toLowerCase() + '/' + auto.modelName.toLowerCase() + '/' + auto.year + '/' + auto.code)
}
</script>
<style scoped>
td, th {
  padding: 18px 0.75rem;;
}

td {
  cursor: pointer;
}
</style>
