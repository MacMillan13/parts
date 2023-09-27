<template>
  <div id="search-block">
    <SearchIcon id="search-icon" /><input id="search-input" class="form-control ds-input" type="text" v-model="search" @input="typingSearch($event)" placeholder="Part number, vin, auto">
    <button v-if="search.length > 0" class="btn btn-primary active">Search</button>
  </div>
</template>
<script>
import {ref} from 'vue';
import {useStore} from 'vuex';
import Search from "../Search.vue";
import SearchIcon from "../icons/search-icon.vue";

export default {
  name: "FindAuto",
  components: { SearchIcon },
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

<style lang="scss" scoped>
#search-block {
  display: flex;
  justify-content: center;
  flex-direction: row;
  position: relative;
  align-items: center;

  button {
    display: flex;
    height: 56px;
    padding: 0 44px;
    justify-content: center;
    align-items: center;
    gap: 8px;
  }

  button, #search-input {
    border-radius: 1px;
  }

  #search-input {
    height: 56px;
    color: var(--grey-grey-90, #0E0E0E);
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 24px; /* 150% */
    padding-left: 55px;
  }

  #search-icon {
    position: absolute;
    left: 15px;
  }
}
</style>
