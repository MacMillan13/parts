import {createApp} from 'vue';
import router from './router/router';
import { createStore } from 'vuex';
import { search } from './store/search'
import App from "./App.vue";

const store = createStore({
  modules: {
    search: search
  }
})

const app = createApp(App);

app.use(store);
app.use(router);
app.mount("#app");
