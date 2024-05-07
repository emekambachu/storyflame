import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios, { isAxiosError } from 'axios'
import { createPinia } from 'pinia'
import piniaPluginPersistedState from 'pinia-plugin-persistedstate'
import { useAuthStore } from './stores/auth'
import '@/assets/app.css'
import { createLogger } from 'vue-logger-plugin'
import modalPlugin from '@/plugins/modalPlugin'
import { VueMountable } from 'vue-mountable'

// Enable CSRF token
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const pinia = createPinia().use(piniaPluginPersistedState)

const logger = createLogger({
    enabled: true,
    level: import.meta.env.MODE === 'development' ? 'debug' : 'error',
})

const app = createApp(App)
    .use(router)
    .use(pinia)
    .use(logger)
    .use(modalPlugin)
    .use(VueMountable())

axios.interceptors.response.use(
    (response) => {
        return response
    },
    (error) => {
        if (isAxiosError(error)) {
            console.log('🚔 interceptor', error.response?.status)
            if (
                error.response?.status === 419 ||
                error.response?.status === 401
            ) {
                if (error.response.status === 419) {
                    console.log('Session expired')
                }
                const authStore = useAuthStore()
                // invalidate the user right now
                if (authStore.isLoggedIn) {
                    authStore.logout().then((r) => {
                        void router.push({ name: 'login' })
                    })
                }
                authStore.user = null
                void router.push({ name: 'login' })
            }
        }
        return Promise.reject(error)
    }
)

app.mount('body')
