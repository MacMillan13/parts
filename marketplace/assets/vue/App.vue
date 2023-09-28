<template>
  <div class="container">
      <router-view />
  </div>
</template>

<script setup>

</script>

<style lang="scss">
  .item-tab {
    padding: 7.5px 15px;
    font-size: 14px;
    display: flex;
    justify-content: center;
    cursor: pointer;

    a {
      color: #3b3b3b;
    }
  }

  #search-btn {
    display: flex;
    height: 56px;
    padding: 0 44px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 1px;
  }

  .item-tab:hover {
    background: #E0E0E0;
  }

  .block-margin {
    margin: 24px 0;
  }
</style>
