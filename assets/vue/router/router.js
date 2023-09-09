import {createRouter, createWebHistory} from 'vue-router'
import AutoModels from "../components/search/AutoModels.vue";
import Search from "../components/Search.vue";
import Example from "../components/Example.vue";
import AutoCatalogModel from "../components/search/AutoCatalogModel.vue";
import AutoCatalogYear from "../components/search/AutoCatalogYear.vue";

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
      component: Example
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