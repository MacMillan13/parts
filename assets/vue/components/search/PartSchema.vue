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
              {{position.number }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "PartSchema",
  props: {
    partSchema: {
      type: Object,
      required: true
    },
    partSchemaPositions: {
      type: Object,
      required: true
    },
  },
}
</script>
<style scoped>
</style>
