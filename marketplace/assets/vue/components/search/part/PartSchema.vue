<template>
  <div class="container">
    <div class="row">
      <div class="col-sm-5">
        <ul class="part-group-ul">
          <li v-for="(partGroup, keyPartGroup) in partSchema.unitedPartGroups ">
            <div class="part-block-position-number">
              <span>{{ keyPartGroup }}</span>
            </div>
            <div v-for="(partBlock, keyPartBlock) in partGroup">
              <div>
                <span>{{ keyPartBlock }}</span>
              </div>
              <div v-for="(partItem, keyPartItem) in partBlock">
                <div>
                  <span>{{ keyPartItem }}</span>
                </div>
                <div v-for="part in partItem">
                  <div>
                    <span>{{ part.code }}</span>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="col-sm-7">
        <div id="part-image-block">
          <img id="part-image" :src="partSchema.img" alt="schema">
          <ul id="part-image-ul">
            <li v-for="position in partSchemaPositions">
              {{ position.number }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>

import {useStore} from 'vuex'
import {computed} from 'vue';
import {useRoute} from 'vue-router'

const store = useStore()
const route = useRoute()

let params = {}

params.model = route.params.model
params.brand = route.params.brand
params.year = route.params.year
params.code = route.params.code
params.partCategory = route.params.partCategory
params.partSchema = route.params.partSchema

store.dispatch('search/getPartGroup', params)

const partSchema = computed(() => store.state.search.partSchema)
const partSchemaPositions = computed(() => store.state.search.partSchemaPositions)

</script>
<style scoped>

.part-group-ul li {
}

.part-group-ul {
  height: 70vh;
  overflow-x: hidden;
  overflow-y: auto;
  text-align: justify;
}

.part-block-position-number {
  margin-right: 15px;
}

#part-image-block {
  display: flex;
  justify-content: center;
}

#part-image {
  max-width: 100%;
  max-height: 70vh;
  width: 100%;
}

#part-image-ul {
  position: absolute;
  top: 0;
  left: 0;
  margin: 0;
  padding: 0;
  list-style: none;
}
</style>
