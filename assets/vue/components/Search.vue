<template>
  <div class="container">
  </div>
</template>
<script>

import FindAuto from "./search/FindAuto.vue";
import AutoFilter from "./search/AutoFilter.vue";

export default {
  name: 'Search',
  components: {
    FindAuto,
    AutoFilter
  },
  setup() {

    return {
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
