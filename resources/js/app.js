import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';

import App from './App.vue';

// Import Supabase and auth
import './supabase';
import { initAuthListener } from './auth';

// Initialize auth listener
initAuthListener();

// Create Vue app
const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

app.mount('#app');
