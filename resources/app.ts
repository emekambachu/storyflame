import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios, { isAxiosError } from 'axios'
import { createPinia } from 'pinia'
import piniaPluginPersistedState from 'pinia-plugin-persistedstate'
import { useAuthStore } from './stores/auth'

// Enable CSRF token
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true


const pinia = createPinia()
pinia.use(piniaPluginPersistedState)
const app = createApp(App)

app.use(router)
app.use(pinia)

axios.interceptors.response.use((response) => {
    return response
}, (error) => {
    if (isAxiosError(error)) {
        console.log('interceptor', error.response.status)
        if (error.response.status === 419 || error.response.status === 401) {
            if (error.response.status === 419) {
                console.log('Session expired')
            }
            const authStore = useAuthStore()
            if (authStore.isLoggedIn) {
                authStore.logout().then(r => {
                    void router.push({ name: 'login' })
                })
            }
        }
    }
    return Promise.reject(error)
})

app.mount('body')
