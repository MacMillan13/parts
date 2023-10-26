<template>
  <div>
    <table class="table">
      <thead>
      <tr>
        <th v-for="header in autoListTableHeader" scope="col">{{ header.name }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-if="autoList !== null" v-for="auto in autoList" @click="getCatalog(auto)">
        <td v-for="header in autoListTableHeader">{{ auto[header.key] }}</td>
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

const autoList = computed(() => store.state.search.autoList);
const autoListTableHeader = computed(() => store.state.search.autoListTableHeader);

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
