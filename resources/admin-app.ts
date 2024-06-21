import { createApp, provide } from 'vue';
import AdminApp from './AdminApp.vue';
import router from './router/admin-routes';
import axios, { isAxiosError } from 'axios';
import { createPinia } from 'pinia';
import piniaPluginPersistedState from 'pinia-plugin-persistedstate';
import '@/assets/app.css';
import { createLogger } from 'vue-logger-plugin';
import modalPlugin from '@/plugins/modalPlugin';
import { VueMountable } from 'vue-mountable';
import { VueQueryPlugin } from '@tanstack/vue-query';
import { defineAsyncComponent } from 'vue';
import store from "./store";

// Enable CSRF token
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const pinia = createPinia().use(piniaPluginPersistedState)

const logger = createLogger({
    enabled: true,
    level: import.meta.env.MODE === 'development' ? 'debug' : 'error',
});

const adminApp = createApp(AdminApp)
    .use(router)
    .use(VueQueryPlugin)
    .use(pinia)
    .use(logger)
    .use(modalPlugin)
    .use(VueMountable())
    .use(store);

const AdminLoginView = defineAsyncComponent(() => import('@/views/admin/pages/AdminLoginView.vue'));
adminApp.component('admin-login-view', AdminLoginView);

adminApp.mount('#admin-app');
