import {createApp} from 'vue';
import Example from './components/Example.vue';
import Search from './components/Search.vue';
import { createStore } from 'vuex';
import { search } from './store/search'

const store = createStore({
  modules: {
    search: search
  }
})

const app = createApp({
  components: {
    Example,
    Search
  }
});

app.use(store);
app.mount("#app");
