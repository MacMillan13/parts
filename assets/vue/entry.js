import {createApp} from 'vue';
import Example from './components/Example.vue';
import Search from './components/Search.vue';

const app = createApp({
  components: {
    Example,
    Search
  }
});

app.mount("#app");
