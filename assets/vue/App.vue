<template>
  <div>
    <router-view />
  </div>
</template>

<script>
import Search from "./components/Search.vue";
import AutoModels from "./components/search/AutoModels.vue";
export default {
  name: "App",
  components: {
    Search,
    AutoModels
  }
};
</script>
