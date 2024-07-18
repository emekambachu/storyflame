import { createApp, provide } from 'vue';
import App from './App.vue';
import router from './router';
import axios, { isAxiosError } from 'axios';
import { createPinia } from 'pinia';
import piniaPluginPersistedState from 'pinia-plugin-persistedstate';
import { useAuthStore } from './stores/auth';
import '@/assets/app.css';
import { createLogger } from 'vue-logger-plugin'
import modalPlugin from '@/plugins/modalPlugin'
import { VueMountable } from 'vue-mountable'
import { VueQueryPlugin } from '@tanstack/vue-query'

// Sentry SDK
// import * as Sentry from "@sentry/vue";
// Sentry.init({
//     dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
// });


// Enable CSRF token
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const pinia = createPinia().use(piniaPluginPersistedState)

const logger = createLogger({
    enabled: true,
    level: import.meta.env.MODE === 'development' ? 'debug' : 'error',
});

const app = createApp(App)
    .use(router)
    .use(VueQueryPlugin)
    .use(pinia)
    .use(logger)
    .use(modalPlugin)
    .use(VueMountable());

app.config.errorHandler = (err, vm, info) => {
    console.error('Global error:', err);
    console.error('Vue component where error occurred:', vm);
    console.error('Vue info:', info);
    // You could also log this to an error tracking service
};

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (isAxiosError(error)) {
            console.log('🚔 interceptor', error.response?.status);
            const authStore = useAuthStore();

            switch (error.response?.status) {
                case 419:
                    console.log('CSRF token mismatch');
                    break;
                case 401:
                    console.log('Unauthorized');
                    break;
                case 403:
                    console.log('Forbidden');
                    break;
                case 404:
                    console.log('Not found');
                    break;
                case 500:
                    console.log('Server error');
                    break;
                default:
                    if (!error.response) {
                        console.log('Network error');
                    } else {
                        console.log('An error occurred');
                    }
            }

            if (error.response?.status === 419 || error.response?.status === 401) {
                if (authStore.isLoggedIn) {
                    authStore.logout().then(() => {
                        router.push({ name: 'login', query: route.query });
                    });
                } else {
                    router.push({ name: 'login', query: route.query});
                }
            }
        }
        return Promise.reject(error);
    }
);

app.mount('body');
