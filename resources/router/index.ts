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

const checkNotOnboarded = (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
) => {
    const logger = useLogger()
    const auth = useAuthStore()
    if (auth.isLoggedIn && !auth.user?.onboarded) {
        logger.info('User is not onboarded')
        return next()
    }
    return next({ name: 'profile' })
}

const checkOnboarded = (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
) => {
    const logger = useLogger()
    const auth = useAuthStore()
    if (auth.isLoggedIn && auth.user?.onboarded) {
        logger.info('User is onboarded')
        return next()
    }
    return next({ name: 'onboarding' })
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
            beforeEnter: [checkAuth, checkNotOnboarded],
            component: () => import('../views/Onboarding.vue'),
        },
        {
            path: '/',
            beforeEnter: [checkAuth, checkOnboarded],
            children: [
                {
                    path: '/profile',
                    name: 'profile',
                    component: () => import('../views/UserProfile.vue'),
                },
                {
                    path: '/stories/new',
                    name: 'new-story',
                    meta: {
                        transition: 'slide',
                        back: 'profile',
                    },
                    beforeEnter: checkAuth,
                    component: () => import('../views/NewStory.vue'),
                },
            ],
        },
        {
            path: '/story/:id',
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
            path: '/plot/:id',
			name: 'plot',
			// beforeEnter: checkAuth,
			component: () => import('../views/PlotView.vue'),
		},
		{
			path: '/theme/:id',
			name: 'theme',
			// beforeEnter: checkAuth,
			component: () => import('../views/ThemeView.vue'),
		},
		{
			path: '/character/:id',
			name: 'character',
			// beforeEnter: checkAuth,
			component: () => import('../views/CharacterView.vue'),
		},
		{
			path: '/achievements',
			name: 'achievements',
			// beforeEnter: checkAuth,
			component: ()=> import('../views/AchievementsListPage.vue')
		},
		{
			path: '/stories',
			name: 'stories',
			// beforeEnter: checkAuth,
			component: ()=> import('../views/StoriesListPage.vue')
		},
		{
			path: '/characters',
			name: 'characters',
			// beforeEnter: checkAuth,
			component: ()=> import('../views/CharacterListPage.vue')
		},
		// {
		// 	path: '/sequences',
		// 	name: 'sequences',
		// 	// beforeEnter: checkAuth,
		// 	component: ()=> import('../views/SequencesListPage.vue')
		// },
		{
			path: '/themes',
			name: 'themes',
			// beforeEnter: checkAuth,
			component: ()=> import('../views/ThemesListPage.vue')
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

router.beforeEach((to, from, next) => {
    // scroll to top on route change
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    })
    next()
})

export default router
