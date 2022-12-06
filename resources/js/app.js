import './bootstrap';
import { createApp } from 'vue';

import v_app from './Components/App.vue';
import router from "./router";

const app = createApp(v_app);
app.use(router);
app.mount('#app');
