import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/HomeView.vue'),
        },
        {
            path: '/whisper',
            name: 'whisper',
            component: () => import('../views/WhisperView.vue'),
        },
        {
            path: '/onboard',
            name: 'onboard',
            component: () => import('../views/Onboarding.vue'),
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/HomeView.vue'),
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('../views/HomeView.vue'),
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: () => import('../views/HomeView.vue'),
        },
    ],
})

export default router
