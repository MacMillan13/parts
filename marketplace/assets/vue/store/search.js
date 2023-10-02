import { defaultDataApi } from '../constants/server'
export const state = () => ({
  step: 0,
  autoList: null,
  autoByVin: null,
  partCatalog: null,
  autoBrands: null,
  autoModel: null,
  autoFilter: null,
  partSchema: null,
  partSchemaPositions: null,
  partCatalogGroup: null,
  autoListTableHeader: null
})

export const getters = {
}

const updateAutoListStructure = (autoList) => {
  if (null === autoList) {
    throw new Error('Auto list is empty');
  }
  autoList.forEach(element => {
    element.parameters.forEach(parameter => {
      element[parameter.key] = parameter.value;
    });
  });

  return autoList;
}

export const mutations = {
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
  setAutoListTableHeader: (state, autoListTableHeader) => {
    state.autoListTableHeader = autoListTableHeader
  },
  setPartCatalog: (state, partCatalog) => {
    state.partCatalog = partCatalog
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
  setAutoByVin: (state, auto) => {
    state.autoByVin = auto
  }
}

export const actions = {

  handleAutoListTableHeader({commit}, autoList) {
    let autoListTableHeader = []

    autoList[0].parameters.forEach(parameter => {
      autoListTableHeader.push({key: parameter.key, name: parameter.name})
    });

    commit("setAutoListTableHeader", autoListTableHeader)
  },

// by vin

  async getDataByVin({ commit }, vinCode) {
    const response = await fetch(defaultDataApi + 'search/vin/' + vinCode, getRequestOptions('GET'));
    const responseJson = await response.json();

    if (false === responseJson.exactMatch) {

      const autoList = updateAutoListStructure(responseJson.data[0].carList)

      commit("setAutoList", autoList)

    } else if (true === responseJson.exactMatch) {

      commit("setAutoByVin", responseJson.data)
    }
  },
  async getPartCatalogByVin({ commit }, vinCode) {
    const response = await fetch(defaultDataApi + 'part/catalog-vin/' + vinCode, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;

    if (data.exactMatch) {
      commit('setPartCatalog', data.catalog)
    } else {
      console.log('not exact match')
    }
  },

  async getPartCatalogGroupByVin({ commit }, params) {
    const response = await fetch(defaultDataApi + 'part/catalog-group-vin/' + params.vin + '/' + params.partCategory, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;
    const partCatalogGroup = data.catalog;

    commit('setPartCatalogGroup', partCatalogGroup)
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

  async getPartSchemaByVin({ commit }, params) {

    commit('setPartSchema', null)
    commit('setPartSchemaPositions', null)

    let url = defaultDataApi + 'part/schema-vin/' + params.vin + '/'
        + params.partCategory

    if (undefined !== params.subCategory) {
      url += '/' + params.subCategory
    }

    url += '/' + params.partSchema

    const response = await fetch(url, getRequestOptions('GET'));

    const responseJson = await response.json();
    const partSchema = responseJson.data;

    commit('setPartSchema', unitUnits(partSchema))
    commit('setPartSchemaPositions', partSchema.positions)
  },

// get auto

  async getAutoCatalogYear({ commit }, params) {
    const response = await fetch(defaultDataApi + 'auto/catalog-year/' + params.brand + '/' + params.model + '/' + params.year, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;

    const autoList = updateAutoListStructure(data.autoList)
    const updatedAutoFilter = autoFilterParametersUpdate(data.parameters)

    commit('setAutoFilter', updatedAutoFilter)
    commit('setAutoList', autoList)

    actions.handleAutoListTableHeader({ commit }, autoList);
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

    actions.handleAutoListTableHeader({ commit }, autoList);
  },

  // get part

  async getPartCatalog({ commit }, params) {
    const response = await fetch(defaultDataApi + 'part/catalog-code/' + params.brand + '/' + params.model + '/' + params.year + '/' + params.code, getRequestOptions('GET'));
    const responseJson = await response.json();
    const data = responseJson.data;

    commit('setPartCatalog', data.catalog)
  },

  async getPartCatalogGroup({ commit }, params) {
    let url = defaultDataApi + 'part/catalog-group/'  + params.brand + '/' + params.model
        + '/' + params.year + '/' + params.code + '/' + params.partCategory

    if (undefined !== params.subCategory) {
      url += '/' + params.subCategory
    }

    const response = await fetch(url,
        getRequestOptions('GET'));

    const responseJson = await response.json();
    const data = responseJson.data;
    const partCatalogGroup = data.catalog;

    commit('setPartCatalogGroup', partCatalogGroup)
  },

  async getPartSchema({ commit }, params) {

    commit('setPartSchema', null)
    commit('setPartSchemaPositions', null)

    let url = defaultDataApi + 'part/schema/' + params.brand + '/'
        + params.model + '/' + params.year + '/' + params.code + '/' + params.partCategory

    if (undefined !== params.subCategory) {
      url += '/' + params.subCategory
    }

    url += '/' + params.partSchema

    const response = await fetch(url,
        getRequestOptions('GET'));

    const responseJson = await response.json();
    const partSchema = responseJson.data;

    commit('setPartSchema', unitUnits(partSchema))
    commit('setPartSchemaPositions', partSchema.positions)
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

  return partSchema;
};

export const search = {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}

