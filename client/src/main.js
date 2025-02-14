import './assets/main.css';
import 'primeicons/primeicons.css';
import { VueQueryPlugin, QueryClient } from '@tanstack/vue-query';

import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import router from './router';

import { createApp } from 'vue';
import App from './App.vue';

const app = createApp(App);
const queryClient = new QueryClient();

app.use(VueQueryPlugin, { queryClient });

app.use(router);
app.use(Toast);

app.mount('#app');