<template>
  <div>
      <div>
        <span>Find the part</span>
        <input type="text" v-model="search" @input="typingSearch($event)" placeholder="Part number, vin, auto">
        <button>Search</button>
      </div>
      <div>
        <div v-if="step == 1 && (autoList !== null || selectedAuto !== null)">
          <table class="table">
            <thead>
            <tr>
              <th scope="col">Brand</th>
              <th scope="col">Model designation</th>
              <th scope="col">Year</th>
              <th scope="col">Region</th>
              <th scope="col">Steering</th>
              <th scope="col">Transmission type</th>
              <th scope="col">Sunroof</th>
              <th scope="col">Navigation</th>
              <th scope="col">Electronic Stability Control (VSA)</th>
              <th scope="col">Door count</th>
              <th scope="col">ABS</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="autoList !== null" v-for="auto in autoList" @click="getCatalog(auto)">
              <td>{{ auto.brand }}</td>
              <td>{{ auto.name }}</td>
              <td>{{ auto.year }}</td>
              <td>{{ auto.sales_region }}</td>
              <td>{{ auto.steering }}</td>
              <td>{{ auto.trans_type }}</td>
              <td>{{ auto.sunroof }}</td>
              <td>{{ auto.navigation }}</td>
              <td>{{ auto.vsa }}</td>
              <td>{{ auto.door_count }}</td>
              <td>{{ auto.abs }}</td>
            </tr>
            <tr v-if="selectedAuto !== null">
              <td>{{ selectedAuto.brand }}</td>
              <td>{{ selectedAuto.name }}</td>
              <td>{{ selectedAuto.year }}</td>
              <td>{{ selectedAuto.sales_region }}</td>
              <td>{{ selectedAuto.steering }}</td>
              <td>{{ selectedAuto.trans_type }}</td>
              <td>{{ selectedAuto.sunroof }}</td>
              <td>{{ selectedAuto.navigation }}</td>
              <td>{{ selectedAuto.vsa }}</td>
              <td>{{ selectedAuto.door_count }}</td>
              <td>{{ selectedAuto.abs }}</td>
            </tr>
            </tbody>
          </table>
        </div>
        <div v-if="step == 2 && autoCatalog !== null">
          <ul>
            <li v-for="catalog in autoCatalog" @click="getCatalogGroup(catalog)">
              {{ catalog.name }}
            </li>
          </ul>
        </div>
        <div v-if="step == 3 && autoCatalogGroup !== null">
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
        </div>
        <div v-if="step == 4 && partSchema !== null">
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
        </div>
        <div>
        </div>
      </div>
    </div>
</template>
<script>

import { ref } from 'vue'

export default {
  name: "search",
  setup() {
    const step = ref(0)
    const vinCodeLength = 17
    const autoList = ref(null)
    const defaultDataApi = ref('https://localhost:8000/api/v3/')
    const autoCatalog = ref(null)
    const autoCatalogGroup = ref(null)
    const selectedAuto = ref(null)
    const partSchema = ref(null)
    const partSchemaPositions = ref(null)
    const search = ref('')
    const currentAuto = ref(null)

    const checkUrl = () => {
      const brand = getParameterFromUrl('brand');
      const catalog = getParameterFromUrl('catalog');
      const auto = getParameterFromUrl('auto');
      const group = getParameterFromUrl('group');

      currentAuto.value = {
        catalogId: brand,
        id: auto
      };

      if (null !== brand && null !== auto && catalog == null && group == null) {

        const catalogParam = {
          id: catalog,
        };

        getCatalog(catalogParam);
      }

      if (null !== brand && null !== auto && null !== catalog) {

        const catalogParam = {
          id: catalog,
        };

        getCatalog(catalogParam, false);
        getCatalogGroup(catalogParam);
      }

      if (null !== brand && null !== auto && null !== group) {

        const groupParam = {
          id: group,
        };

        getPartGroup(groupParam);
      }
    }

    const getParameterFromUrl = (value) => {
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      return urlParams.get(value);
    }

    const getRequestOptions = (method) => {
      return {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'Accept-Language': 'en'
        }
      };
    }

    const typingSearch = async() => {
      if (search.value.length === vinCodeLength) {
        console.log('Might be vin');
        await getDataByVin(search.value);
      }
    }

    const getDataByVin = async(vinCode) => {
      const response = await fetch(defaultDataApi.value + 'search/vin/' + vinCode, getRequestOptions('GET'));
      const responseJson = await response.json();

      if (false === responseJson.exactMatch) {
        autoList.value = responseJson.data[0].carList;
        autoList.value.forEach(element => {
          element.parameters.forEach(parameter => {
            element[parameter.key] = parameter.value;
          });
        });
      } else if (true === responseJson.exactMatch) {
        await getCatalog(responseJson.data);
      }

      step.value = 1;
    }

    const getCatalog = async (auto, setStep = true) => {
      //TODO uncomment when will have API.
      // const response = await fetch(this.defaultDataApi + 'part/catalog/' + auto.catalogId + '/' + auto.id,
      //   getRequestOptions('GET'));
      const response = await fetch(defaultDataApi.value + 'part/catalog/skoda/c9c4f4d0fe26e3af5aa36af8c197b096',
          getRequestOptions('GET'));
      const responseJson = await response.json();
      autoCatalog.value = responseJson.data;
      selectedAuto.value = auto;
      if (setStep) {
        step.value = 2;
      }
    }

    const getCatalogGroup = async(catalog) => {
      //TODO uncomment when will have API.
      // const response = await fetch(this.defaultDataApi + 'part/catalog/' + auto.catalogId + '/' + auto.id + '/' + catalog.id,
      //   getRequestOptions('GET'));
      const response = await fetch(defaultDataApi.value + 'part/catalog/skoda/c9c4f4d0fe26e3af5aa36af8c197b096/MfCfmoAw',
          getRequestOptions('GET'));
      const responseJson = await response.json();
      autoCatalogGroup.value = responseJson.data;
      step.value = 3;
    }

    const getPartGroup = async(partGroup) => {
      // const response = await fetch(this.defaultDataApi + 'search/part/schema/' + this.selectedAuto.catalogId + '/'
      //   + this.selectedAuto.id + '/' + partGroup.id,
      //   this.getRequestOptions('GET'));
      const response = await fetch(defaultDataApi.value + 'search/part/schema/skoda/c9c4f4d0fe26e3af5aa36af8c197b096/MCPwn5qAMTAwOTXwn5qBNDYwMTAwOTgw8J-agjYxMjUzMfCflLA2MDc6MTAwOTU4MDU0NjAxMDA5ODDwn5CSNjA38J-QiTEwMDk1ODA1NDYwMTAwOTgw',
          getRequestOptions('GET'));
      const responseJson = await response.json();
      partSchema.value = responseJson.data;
      partSchemaPositions.value = partSchema.positions;
      unitUnits(partSchema);
      step.value = 4;
    }

    const unitUnits = (partSchema) => {
      let parts = {};

      partSchema.value.partGroups.forEach(partGroup => {
        partGroup.parts.forEach(part => {
          if (parts[part.positionNumber] === undefined) {
            parts[part.positionNumber] = {};
          }
          if (parts[part.positionNumber][partGroup.name] === undefined) {
            parts[part.positionNumber][partGroup.name] = {};
          }
          if (parts[part.positionNumber][partGroup.name][part.name] === undefined) {
            parts[part.positionNumber][partGroup.name][part.name] = [];
          }

          parts[part.positionNumber][partGroup.name][part.name].push({
            'code': part.number
          });
        });
      });

      partSchema.value.unitedPartGroups = parts;
    }

    checkUrl();

    return {
      step,
      vinCodeLength,
      defaultDataApi,
      autoList,
      autoCatalog,
      autoCatalogGroup,
      selectedAuto,
      partSchema,
      partSchemaPositions,
      search,
      typingSearch,
      getCatalog,
      getCatalogGroup,
      getPartGroup
    }
  }
}
</script>

<style scoped>
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

.part-group-ul li {
}

.part-group-ul {
  height: 70vh;
  overflow-x: hidden;
  overflow-y: auto;
  text-align: justify;
}

.part-block-ul li:first-child {
  border-top: 0.5px solid rgba(0, 64, 96, 0.48);
}

.part-block-ul li {
  display: flex;
  border-bottom: 0.5px solid rgba(0, 64, 96, 0.48);
}

.part-block-position-number {
  margin-right: 15px;
}

.group-title {
  text-transform: uppercase;
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

.catalog-group-block {
  display: flex;
  flex-direction: column;
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
