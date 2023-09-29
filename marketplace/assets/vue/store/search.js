import { defaultDataApi } from '../constants/server'
export const state = () => ({
  step: 0,
  autoList: null,
  partCatalog: null,
  selectedAuto: null,
  autoBrands: null,
  autoModel: null,
  autoFilter: null,
  partSchema: null,
  partSchemaPositions: null,
  partCatalogGroup: null
})

export const getters = {
}

const updateAutoListStructure = (autoList) => {

  autoList.forEach(element => {
    element.parameters.forEach(parameter => {
      element[parameter.key] = parameter.value;
    });
  });

  return autoList;
}

export const mutations = {
  setStep: (state, step) => {
    state.step = step
  },
  setAutoBrands: (state, autoBrands) => {
    state.autoBrands = autoBrands
  },
  setAutoModel: (state, autoModel) => {
    state.autoModel = autoModel
  },
  setAutoFilter: (state, autoFilter) => {
    state.autoFilter = autoFilter
  },
  setAutoList: (state, autoList) => {
    state.autoList = autoList
  },
  setPartCatalog: (state, partCatalog) => {
    state.partCatalog = partCatalog
  },
  setSelectedAuto: (state, selectedAuto) => {
    state.selectedAuto = selectedAuto
  },
  setPartCatalogGroup: (state, partCatalogGroup) => {
    state.partCatalogGroup = partCatalogGroup
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

      const autoList = updateAutoListStructure(responseJson.data[0].carList)

      commit("setAutoList", autoList)

    } else if (true === responseJson.exactMatch) {

      await this.getCatalog({ commit },{
        auto: responseJson.data,
        toSet: true
      });
    }

    commit('setStep', 1)
  },

  async getDataPart({ commit }, partNumber) {
    const response = await fetch(defaultDataApi + 'search/vin/' + vinCode, getRequestOptions('GET'));
    const responseJson = await response.json();

    if (false === responseJson.exactMatch) {

      const autoList = updateAutoListStructure(responseJson.data[0].carList)

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
    const partCatalog = responseJson.data;
    commit("setPartCatalog", partCatalog)
    commit("setSelectedAuto", params.carId)

    if (params.toSet) {
      const url = new URL(location.origin);
      url.searchParams.set('brand', 'skoda');
      url.searchParams.set('auto', 'c9c4f4d0fe26e3af5aa36af8c197b096');
      history.pushState({}, '', url);

      commit('setStep', 2)
    }
  },

  async getAutoBrands({ commit }) {
    const response = await fetch(defaultDataApi + 'auto/brand', getRequestOptions('GET'));
    const responseJson = await response.json();
    commit('setAutoBrands', responseJson.data)
  },

  async getAutoModels({ commit }, autoModel) {
    const response = await fetch(defaultDataApi + 'auto/model/' + autoModel, getRequestOptions('GET'));
    const responseJson = await response.json();
    commit('setAutoModel', responseJson.data)
  },

  async getAutoCatalogModel({ commit }, params) {
    const response = await fetch(defaultDataApi + 'auto/catalog-model/' + params.brand + '/' + params.model, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;

    const autoList = updateAutoListStructure(data.autoList)
    const updatedAutoFilter = autoFilterParametersUpdate(data.parameters)

    commit('setAutoFilter', updatedAutoFilter)
    commit('setAutoList', autoList)
  },

  async getAutoCatalogYear({ commit }, params) {
    const response = await fetch(defaultDataApi + 'auto/catalog-year/' + params.brand + '/' + params.model + '/' + params.year, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;

    const autoList = updateAutoListStructure(data.autoList)
    const updatedAutoFilter = autoFilterParametersUpdate(data.parameters)

    commit('setAutoFilter', updatedAutoFilter)
    commit('setAutoList', autoList)
  },

  async getPartCatalog({ commit }, params) {
    const response = await fetch(defaultDataApi + 'part/catalog-code/' + params.brand + '/' + params.model + '/' + params.year + '/' + params.code, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;

    commit('setPartCatalog', data.catalog)
  },

  async getAutoCatalog({ commit }, params) {
    let url = defaultDataApi + 'auto/catalog/' + params.brand + '/' + params.model
    if (undefined !== params.query) {
      url += params.query
    }
    const response = await fetch(url, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;

    const autoList = updateAutoListStructure(data.autoList)
    const updatedAutoFilter = autoFilterParametersUpdate(data.parameters)

    commit('setAutoFilter', updatedAutoFilter)
    commit('setAutoList', autoList)
  },

  async getPartCatalogGroup({ commit }, params) {
    const response = await fetch(defaultDataApi + 'part/catalog-group/'  + params.autoParams.brand + '/' + params.autoParams.model
        + '/' + params.autoParams.year + '/' + params.autoParams.code + '/' + params.catalog.name.toLowerCase(),
        getRequestOptions('GET'));
  },

  async getCatalogGroup({ commit }, params) {
    //TODO uncomment when will have API.
    // const response = await fetch(this.defaultDataApi + 'part/catalog/' + auto.catalogId + '/' + auto.id + '/' + catalog.id,
    //   getRequestOptions('GET'));
    const response = await fetch(defaultDataApi + 'part/catalog/skoda/c9c4f4d0fe26e3af5aa36af8c197b096/MfCfmoAw',
        getRequestOptions('GET'));
    const responseJson = await response.json();

    commit("setPartCatalogGroup", responseJson.data)

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

const autoFilterParametersUpdate = (parameters) => {
  let dataWithExistedValue = []

  parameters.forEach(value => {
    if (value.values.length > 1) {
      dataWithExistedValue.push(value)
    }
  })

  return dataWithExistedValue
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

