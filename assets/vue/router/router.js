import {createRouter, createWebHistory} from 'vue-router'
import AutoModels from "../components/search/AutoModels.vue";
import Search from "../components/Search.vue";
import Example from "../components/Example.vue";

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
      path: '/:catalogId',
      component: AutoModels
    },
    {
      name: 'Model',
      path: '/:catalogId/:model',
      component: Example
    },
  ]
})