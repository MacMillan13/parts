<template>
  <div>
      <div>
        <span>Find the part</span>
        <input type="text" v-model="search" @input="typingSearch($event)" placeholder="Part number, vin, auto">
        <button>Search</button>
      </div>
      <div>
        <div v-if="step === 1 && (autoList !== null || selectedAuto !== null)">
          <AutoList :autoList="autoList" :selectedAuto="selectedAuto" :getCatalog="getCatalog" />
        </div>
        <div v-if="step === 2 && autoCatalog !== null">
          <ul>
            <li v-for="catalog in autoCatalog" @click="getCatalogGroup(catalog)">
              {{ catalog.name }}
            </li>
          </ul>
        </div>
        <div v-if="step === 3 && autoCatalogGroup !== null">
          <AutoCatalogGroup :autoCatalog="autoCatalog" :autoCatalogGroup="autoCatalogGroup"
                            :getCatalogGroup="getCatalogGroup" :getPartGroup="getPartGroup" />
        </div>
        <div v-if="step === 4 && partSchema !== null">
          <PartSchema :partSchema="partSchema" :partSchemaPositions="partSchemaPositions" />
        </div>
        <div>
        </div>
      </div>
    </div>
</template>
<script>

import { ref } from 'vue'
import AutoList from "./search/AutoList";
import AutoCatalogGroup from "./search/AutoCatalogGroup";
import PartSchema from "./search/PartSchema";

export default {
  name: "search",
  components: { AutoList, AutoCatalogGroup, PartSchema },
  setup() {
    const defaultDataApi = 'https://localhost:8000/api/v3/'
    const vinCodeLength = 17

    const step = ref(0)
    const autoList = ref(null)
    const autoCatalog = ref(null)
    const autoCatalogGroup = ref(null)
    const selectedAuto = ref(null)
    const partSchema = ref(null)
    const partSchemaPositions = ref(null)
    const search = ref('')
    const currentAuto = ref(null)

    const checkUrl = () => {

      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const brand = urlParams.get('brand');
      const catalog = urlParams.get('catalog');
      const auto = urlParams.get('auto');
      const group = urlParams.get('group');

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
      const response = await fetch(defaultDataApi + 'search/vin/' + vinCode, getRequestOptions('GET'));
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
      const response = await fetch(defaultDataApi + 'part/catalog/skoda/c9c4f4d0fe26e3af5aa36af8c197b096',
          getRequestOptions('GET'));
      const responseJson = await response.json();
      autoCatalog.value = responseJson.data;
      selectedAuto.value = auto;

      if (setStep) {
        const url = new URL(location.origin);
        url.searchParams.set('brand', 'skoda');
        url.searchParams.set('auto', 'c9c4f4d0fe26e3af5aa36af8c197b096');
        history.pushState({}, "", url);

        step.value = 2;
      }
    }

    const getCatalogGroup = async(catalog) => {
      //TODO uncomment when will have API.
      // const response = await fetch(this.defaultDataApi + 'part/catalog/' + auto.catalogId + '/' + auto.id + '/' + catalog.id,
      //   getRequestOptions('GET'));
      const response = await fetch(defaultDataApi + 'part/catalog/skoda/c9c4f4d0fe26e3af5aa36af8c197b096/MfCfmoAw',
          getRequestOptions('GET'));
      const responseJson = await response.json();
      autoCatalogGroup.value = responseJson.data;

      const url = new URL(location);

      url.searchParams.delete('group');
      url.searchParams.set('brand', 'skoda');
      url.searchParams.set('auto', 'c9c4f4d0fe26e3af5aa36af8c197b096');
      url.searchParams.set('catalog', 'MfCfmoAw');
      history.pushState({}, "", url);

      step.value = 3;
    }

    const getPartGroup = async(partGroup) => {
      // const response = await fetch(this.defaultDataApi + 'search/part/schema/' + this.selectedAuto.catalogId + '/'
      //   + this.selectedAuto.id + '/' + partGroup.id,
      //   this.getRequestOptions('GET'));
      const response = await fetch(defaultDataApi + 'search/part/schema/skoda/c9c4f4d0fe26e3af5aa36af8c197b096/MCPwn5qAMTAwOTXwn5qBNDYwMTAwOTgw8J-agjYxMjUzMfCflLA2MDc6MTAwOTU4MDU0NjAxMDA5ODDwn5CSNjA38J-QiTEwMDk1ODA1NDYwMTAwOTgw',
          getRequestOptions('GET'));
      const responseJson = await response.json();
      partSchema.value = responseJson.data;
      partSchemaPositions.value = partSchema.positions;
      unitUnits(partSchema);

      const url = new URL(location);

      url.searchParams.delete('catalog');
      url.searchParams.set('brand', 'skoda');
      url.searchParams.set('auto', 'c9c4f4d0fe26e3af5aa36af8c197b096');
      url.searchParams.set('group', 'MCPwn5qAMTAwOTXwn5qBNDYwMTAwOTgw8J-agjYxMjUzMfCflLA2MDc6MTAwOTU4MDU0NjAxMDA5ODDwn5CSNjA38J-QiTEwMDk1ODA1NDYwMTAwOTgw');

      history.pushState({}, "", url);

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
