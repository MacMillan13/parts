
const defaultDataApi = 'https://localhost:8000/api/v3/';
export const state = () => ({
  step: 0,
  autoList: null,
  autoCatalog: null,
  selectedAuto: null,
  autoBrands: null,
  partSchema: null,
  partSchemaPositions: null,
  autoCatalogGroup: null
})

export const getters = {
}

export const mutations = {
  setStep: (state, step) => {
    state.step = step
  },
  setAutoBrands: (state, autoBrands) => {
    state.autoBrands = autoBrands
  },
  setAutoList: (state, autoList) => {
    state.autoList = autoList
  },
  setAutoCatalog: (state, autoCatalog) => {
    state.autoCatalog = autoCatalog
  },
  setSelectedAuto: (state, selectedAuto) => {
    state.selectedAuto = selectedAuto
  },
  setAutoCatalogGroup: (state, autoCatalogGroup) => {
    state.autoCatalogGroup = autoCatalogGroup
  },
  setPartSchema: (state, partSchema) => {
    state.partSchema = partSchema
  },
  setPartSchemaPositions: (state, partSchemaPositions) => {
    state.partSchemaPositions = partSchemaPositions
  },
}

export const actions = {

  async getDataByVin({ commit }, vinCode) {
    const response = await fetch(defaultDataApi + 'search/vin/' + vinCode, getRequestOptions('GET'));
    const responseJson = await response.json();

    if (false === responseJson.exactMatch) {

      const autoList = responseJson.data[0].carList
      autoList.forEach(element => {
        element.parameters.forEach(parameter => {
          element[parameter.key] = parameter.value;
        });
      });
      commit("setAutoList", autoList)

    } else if (true === responseJson.exactMatch) {

      await this.getCatalog({ commit },{
        auto: responseJson.data,
        toSet: true
      });
    }

    commit('setStep', 1)
  },

  async getCatalog({ commit }, params) {
    //TODO uncomment when will have API.
    // const response = await fetch(this.defaultDataApi + 'part/catalog/' + auto.catalogId + '/' + auto.id,
    //   getRequestOptions('GET'));
    const response = await fetch(defaultDataApi + 'part/catalog/skoda/c9c4f4d0fe26e3af5aa36af8c197b096',
        getRequestOptions('GET'));
    const responseJson = await response.json();
    const autoCatalog = responseJson.data;
    commit("setAutoCatalog", autoCatalog)
    commit("setSelectedAuto", params.auto)

    if (params.toSet) {
      const url = new URL(location.origin);
      url.searchParams.set('brand', 'skoda');
      url.searchParams.set('auto', 'c9c4f4d0fe26e3af5aa36af8c197b096');
      history.pushState({}, '', url);

      commit('setStep', 2)
    }
  },

  async getCarBrands({ commit }) {
    const response = await fetch(defaultDataApi + 'car/brand', getRequestOptions('GET'));
    const responseJson = await response.json();
    commit('setAutoBrands', responseJson.data)
  },

  async getCatalogGroup({ commit }, catalog) {
    //TODO uncomment when will have API.
    // const response = await fetch(this.defaultDataApi + 'part/catalog/' + auto.catalogId + '/' + auto.id + '/' + catalog.id,
    //   getRequestOptions('GET'));
    const response = await fetch(defaultDataApi + 'part/catalog/skoda/c9c4f4d0fe26e3af5aa36af8c197b096/MfCfmoAw',
        getRequestOptions('GET'));
    const responseJson = await response.json();

    commit("setAutoCatalogGroup", responseJson.data)

    const url = new URL(location);

    url.searchParams.delete('group');
    url.searchParams.set('brand', 'skoda');
    url.searchParams.set('auto', 'c9c4f4d0fe26e3af5aa36af8c197b096');
    url.searchParams.set('catalog', 'MfCfmoAw');
    history.pushState({}, '', url);

    commit('setStep', 3)
  },

  async getPartGroup({ commit }, partGroup) {
    // const response = await fetch(this.defaultDataApi + 'search/part/schema/' + this.selectedAuto.catalogId + '/'
    //   + this.selectedAuto.id + '/' + partGroup.id,
    //   this.getRequestOptions('GET'));
    const response = await fetch(defaultDataApi + 'search/part/schema/skoda/c9c4f4d0fe26e3af5aa36af8c197b096/MCPwn5qAMTAwOTXwn5qBNDYwMTAwOTgw8J-agjYxMjUzMfCflLA2MDc6MTAwOTU4MDU0NjAxMDA5ODDwn5CSNjA38J-QiTEwMDk1ODA1NDYwMTAwOTgw',
        getRequestOptions('GET'));
    const responseJson = await response.json();
    const partSchema = responseJson.data;

    commit('setPartSchema', partSchema)
    commit('setPartSchemaPositions', partSchema.positions)

    unitUnits(partSchema);

    const url = new URL(location);

    url.searchParams.delete('catalog');
    url.searchParams.set('brand', 'skoda');
    url.searchParams.set('auto', 'c9c4f4d0fe26e3af5aa36af8c197b096');
    url.searchParams.set('group', 'MCPwn5qAMTAwOTXwn5qBNDYwMTAwOTgw8J-agjYxMjUzMfCflLA2MDc6MTAwOTU4MDU0NjAxMDA5ODDwn5CSNjA38J-QiTEwMDk1ODA1NDYwMTAwOTgw');

    history.pushState({}, '', url);

    commit('setStep', 4)
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
};

const unitUnits = (partSchema) => {
  let parts = {};

  partSchema.partGroups.forEach(partGroup => {
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

  partSchema.unitedPartGroups = parts;
};

export const search = {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}

