import './bootstrap';
import { createApp } from 'vue';
import store from './store';
import router from './router';

// Import components
import App from './components/App.vue';

// Create app
const app = createApp(App);

// Add Vuex store and Vue Router
app.use(store);
app.use(router);

// Mount the app
app.mount('#app');
