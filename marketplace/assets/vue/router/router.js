import {createRouter, createWebHistory} from 'vue-router'
import AutoModels from "../components/search/auto/AutoModels.vue";
import Search from "../components/Search.vue";
import Example from "../components/Example.vue";
import AutoCatalogModel from "../components/search/auto/AutoCatalogModel.vue";
import AutoCatalogYear from "../components/search/auto/AutoCatalogYear.vue";
import PartCatalog from "../components/search/part/PartCatalog.vue";
import OffersList from "../components/search/OffersList.vue";

export default createRouter({
  history: createWebHistory(),
  routes: [
    {
      name: 'Search',
      path: '/',
      component: Search
    },
    {
      name: 'Models',
      path: '/:brand',
      component: AutoModels
    },
    {
      name: 'OffersList',
      path: '/part/:partNumber',
      component: OffersList
    },
    {
      name: 'AutoCatalogModel',
      path: '/:brand/:model',
      component: AutoCatalogModel
    },
    {
      name: 'AutoYear',
      path: '/:brand/:model/:year',
      component: AutoCatalogYear
    },
    {
      name: 'AutoModification',
      path: '/:brand/:model/:year/:modification',
      component: PartCatalog
    },
    {
      name: 'AutoPartCategory',
      path: '/:brand/:model/:year/:modification/:partCategory',
      component: Example
    },
    {
      name: 'AutoPart',
      path: '/:brand/:model/:year/:modification/:partCategory/:autoPart',
      component: Example
    },
  ]
})