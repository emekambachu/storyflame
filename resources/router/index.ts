import {
    createRouter,
    createWebHistory,
    NavigationGuardNext,
    RouteLocationNormalized,
} from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useLogger } from 'vue-logger-plugin'

const checkAuth = (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
) => {
    const logger = useLogger()
    const auth = useAuthStore()
    if (!auth.isLoggedIn) {
        logger.info('User is not logged in')
        return next({ name: 'login' })
    }
    return next()
}

const checkGuest = (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
) => {
    const logger = useLogger()
    const auth = useAuthStore()
    if (auth.isLoggedIn) {
        logger.info('User is already logged in')
        return next({ name: 'onboarding' })
    }
    return next()
}

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
			name: 'onboarding',
			beforeEnter: checkAuth,
			component: () => import('../views/Onboarding.vue'),
		},
		{
			path: '/summary',
			name: 'summary',
			beforeEnter: checkAuth,
			component: () => import('../views/OnboardingSummary.vue'),
		},
		{
			path: '/story',
			name: 'story',
			// beforeEnter: checkAuth,
			component: () => import('../views/StoryView.vue'),
		},
		{
			path: '/sequence/:id',
			name: 'sequence',
			// beforeEnter: checkAuth,
			component: () => import('../views/SequenceView.vue'),
		},
		{
			path: '/auth',
			redirect: '/auth/login',
			component: () => import('../views/AuthView.vue'),
			beforeEnter: checkGuest,
			children: [
				{
					path: 'login',
					name: 'login',
					component: () => import('../views/LoginView.vue'),
				},
				{
					path: 'register',
					name: 'register',
					component: () => import('../views/RegisterView.vue'),
				},
			],
		},
		{
			path: '/dashboard',
			name: 'dashboard',
			component: () => import('../views/HomeView.vue'),
		},
		{
			path: '/:pathMatch(.*)*',
			name: 'not-found',
			redirect: { name: 'home' },
		},
	],
})

export default router
