import {createRouter, createWebHistory} from 'vue-router'
import AutoModels from "../components/search/auto/AutoModels.vue";
import Example from "../components/Example.vue";
import AutoCatalogModel from "../components/search/auto/AutoCatalogModel.vue";
import AutoCatalogYear from "../components/search/auto/AutoCatalogYear.vue";
import PartMenuSidebar from "../components/search/part/PartMenuSidebar.vue";
import OffersList from "../components/search/OffersList.vue";
import AutoBrands from "../components/search/auto/AutoBrands.vue";
import PartCatalogGroup from "../components/search/part/PartCatalogGroup.vue";
import PartSchema from "../components/search/part/PartSchema.vue";

export default createRouter({
  history: createWebHistory(),
  routes: [
    {
      name: 'Example',
      path: '/example',
      component: Example
    },
    {
      name: 'AutoBrands',
      path: '/',
      component: AutoBrands
    },
    {
      name: 'VinMenu',
      path: '/vin/:vin',
      component: PartMenuSidebar
    },
    {
      name: 'VinCatalogGroup',
      path: '/vin/:vin/:partCategory',
      component: PartCatalogGroup
    },
    {
      name: 'VinPartShema',
      path: '/vin/:vin/:partCategory/:partSchema',
      component: PartSchema
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
      name: 'PartMenu',
      path: '/:brand/:model/:year/:code',
      component: PartMenuSidebar
    },
    {
      name: 'PartCatalogGroup',
      path: '/:brand/:model/:year/:code/:partCategory',
      component: PartCatalogGroup
    },
    {
      name: 'PartCatalogGroupSubCategory',
      path: '/:brand/:model/:year/:code/:partCategory/:subCategory',
      component: PartCatalogGroup
    },
    {
      name: 'PartSchema',
      path: '/:brand/:model/:year/:code/:partCategory/schema/:partSchema',
      component: PartSchema
    },
    {
      name: 'PartSchemaForSubcategory',
      path: '/:brand/:model/:year/:code/:partCategory/:subCategory/schema/:partSchema',
      component: PartSchema
    },
  ]
})