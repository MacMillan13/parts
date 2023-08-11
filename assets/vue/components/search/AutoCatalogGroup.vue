<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <ul>
          <li v-for="catalog in autoCatalog" @click="getCatalogGroup(catalog)">
            {{ catalog.name }}
          </li>
        </ul>
      </div>
      <div class="row col-sm-9">
        <div v-for="partGroup in autoCatalogGroup" @click="getPartGroup(partGroup)"
             class="catalog-group-block col-sm-4 thumbnail">
          <div>
            <img :src="partGroup.img" alt="Part's group" class="embed-responsive-item">
          </div>
          {{ partGroup.name }}
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "AutoCatalogGroup",
  props: {
    autoCatalog: {
      type: Array,
      required: true
    },
    autoCatalogGroup: {
      type: Array,
      required: true
    },
    getCatalogGroup: {
      type: Function,
      required: true
    },
    getPartGroup: {
      type: Function,
      required: true
    },
  },
}
</script>
<style scoped>
</style>
