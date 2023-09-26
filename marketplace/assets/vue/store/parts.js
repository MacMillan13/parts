import { defaultDataApi } from '../constants/server'
export const state = () => ({
})

export const getters = {
}

export const mutations = {

}

export const actions = {
    async getFromTheShop() {
        const response = await fetch(defaultDataApi + 'search/vin/' + vinCode, getRequestOptions('GET'));
        const responseJson = await response.json();

        if (false === responseJson.exactMatch) {

            const autoList = updateAutoListStructure(responseJson.data[0].carList)

            commit("setAutoList", autoList)

        } else if (true === responseJson.exactMatch) {

            await this.getCatalog({commit}, {
                auto: responseJson.data,
                toSet: true
            });
        }
    }
}

export const parts = {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}

