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
      <tr v-if="selectedAuto !== null">
        <td>{{ selectedAuto.brand }}</td>
        <td>{{ selectedAuto.name }}</td>
        <td>{{ selectedAuto.year }}</td>
        <td>{{ selectedAuto.sales_region }}</td>
        <td>{{ selectedAuto.steering }}</td>
        <td>{{ selectedAuto.trans_type }}</td>
        <td>{{ selectedAuto.sunroof }}</td>
        <td>{{ selectedAuto.navigation }}</td>
        <td>{{ selectedAuto.vsa }}</td>
        <td>{{ selectedAuto.door_count }}</td>
        <td>{{ selectedAuto.abs }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import {computed} from 'vue';
import {useStore} from 'vuex';

export default {
  name: "AutoList",
  setup() {
    const store = useStore()

    const selectedAuto = computed(() => store.state.search.selectedAuto);
    const autoList = computed(() => store.state.search.autoList);
    const getCatalog = async (auto) => {
      await store.dispatch('search/getCatalog', {
        carId: auto,
        toSet: true
      })
    }

    return {
      selectedAuto,
      autoList,
      getCatalog
    }
  }
}
</script>
<style scoped>
</style>
