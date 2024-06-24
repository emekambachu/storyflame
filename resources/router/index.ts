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
            beforeEnter: [checkAuth],
            children: [
                {
                    path: '/profile',
                    name: 'profile',
                    component: () => import('../views/UserProfile.vue'),
                    meta: {
                        transition: 'slide',
                        back: 'home',
                    },
                },
                {
                    path: 'stories/',
                    children: [
                        {
                            path: '',
                            name: 'stories',
                            component: () =>
                                import('../views/StoriesListPage.vue'),
                        },
                        {
                            path: 'new',
                            name: 'new-story',
                            meta: {
                                transition: 'slide',
                            },
                            beforeEnter: checkAuth,
                            component: () => import('../views/NewStory.vue'),
                        },
                        {
                            path: ':story/',
                            children: [
                                {
                                    path: '',
                                    name: 'story',
                                    // beforeEnter: checkAuth,
                                    component: () =>
                                        import('../views/StoryView.vue'),
                                    meta: {
                                        transition: 'slide',
                                        back: 'home'
                                    },
                                },
                                {
                                    path: 'characters/',
                                    children: [
                                        {
                                            path: '',
                                            name: 'characters',
                                            component: () =>
                                                import(
                                                    '../views/CharacterListPage.vue'
                                                ),
                                            meta: {
                                                transition: 'slide',
                                            },
                                        },
                                        {
                                            path: ':character',
                                            name: 'character',
                                            component: () =>
                                                import(
                                                    '../views/CharacterView.vue'
                                                ),
                                            meta: {
                                                transition: 'slide',
                                            },
                                        },
                                    ],
                                },
                                {
                                    path: 'sequences/',
                                    children: [
                                        // {
                                        //     path: '',
                                        //     name: 'sequences',
                                        //     component: () =>
                                        //         import(
                                        //             '../views/SequencesListPage.vue'
                                        //         ),
                                        // },
                                        {
                                            path: ':sequence',
                                            name: 'sequence',
                                            component: () =>
                                                import(
                                                    '../views/SequenceView.vue'
                                                ),
                                        },
                                    ],
                                },
                                {
                                    path: 'plots/',
                                    children: [
                                        {
                                            path: ':plot',
                                            name: 'plot',
                                            component: () =>
                                                import('../views/PlotView.vue'),
                                        },
                                    ],
                                },
                                {
                                    path: 'themes/',
                                    children: [
                                        {
                                            path: '',
                                            name: 'themes',
                                            component: () =>
                                                import(
                                                    '../views/ThemesListPage.vue'
                                                ),
                                        },
                                        {
                                            path: ':theme',
                                            name: 'theme',
                                            component: () =>
                                                import(
                                                    '../views/ThemeView.vue'
                                                ),
                                        },
                                    ],
                                },
                                {
                                    path: 'audiences/',
                                    children: [
                                        {
                                            path: ':id',
                                            name: 'audience',
                                            component: () =>
                                                import(
                                                    '../views/TargetAudienceView.vue'
                                                ),
                                        },
                                    ],
                                },
                            ],
                        },
                    ],
                },
            ],
        },
        {
            path: '/achievement/:id',
            name: 'achievement',
            // beforeEnter: checkAuth,
            component: () => import('../views/AchievementView.vue'),
        },
        {
            path: '/achievements',
            name: 'achievements',
            // beforeEnter: checkAuth,
            component: () => import('../views/AchievementsListPage.vue'),
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
