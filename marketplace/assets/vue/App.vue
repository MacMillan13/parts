<template>
  <div>
      <router-view />
  </div>
</template>

<script setup>
import FindAuto from "./components/search/FindAuto.vue";

</script>

<style lang="scss">
  .auto-tab {
    padding: 7.5px 15px;
    font-size: 14px;
    display: flex;
    justify-content: center;
    cursor: pointer;

    a {
      color: #3b3b3b;
    }
  }

  .auto-tab:hover {
    background: #E0E0E0;
  }
</style>