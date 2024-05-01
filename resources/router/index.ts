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
            path: '/summary',
            name: 'summary',
            component: () => import('../views/OnboardingSummary.vue'),
        },
        {
            path: '/auth',
            redirect: '/auth/login',  
            component: () => import('../views/AuthView.vue'),
            children: [
                {
                    path: 'login',
                    name: 'auth-login',  
                    component: () => import('../views/LoginView.vue')
                },
                {
                    path: 'register',
                    name: 'auth-register',
                    component: () => import('../views/RegisterView.vue')
                }
            ]
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: () => import('../views/HomeView.vue'),
        },
    ],
})

export default router
