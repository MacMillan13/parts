<template>
  <div>
    <span>Find the part</span>
    <input type="text" v-model="search" @input="typingSearch($event)" placeholder="Part number, vin, auto">
    <button>Search</button>
  </div>
</template>
<script>
import {ref} from 'vue';
import {useStore} from 'vuex';

export default {
  name: "FindCar",
  setup() {
    const search = ref('');
    const store = useStore()
    const typingSearch = async () => {

      const vinCodeLength = 17;

      if (search.value.length === vinCodeLength) {

        await store.dispatch('search/getDataByVin', search.value)
      }
    };

    return {
      typingSearch,
      search
    }
  }
}
</script>
<style scoped>
</style>
