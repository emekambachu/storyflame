import {
    useRoute,
    createRouter,
    createWebHistory,
    NavigationGuardNext,
    RouteLocationNormalized,
} from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useLogger } from 'vue-logger-plugin'
import {routes} from "vue-router/vue-router-auto-routes";

// const checkAuth = (
//     to: RouteLocationNormalized,
//     from: RouteLocationNormalized,
//     next: NavigationGuardNext
// ) => {
//     const logger = useLogger()
//     const auth = useAuthStore()
//     if (!auth.isLoggedIn) {
//         logger.info('User is not logged in')
//         return next({ name: 'login' })
//     }
//     return next()
// }

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
        return next({
            name: 'onboarding',
            query: { ...to.query }
        })
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

const preserveQueryParams = (
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
) => {
    if (Object.keys(to.query).length === 0 && Object.keys(from.query).length > 0) {
        next({ ...to, query: { ...from.query } })
    } else {
        next()
    }
}

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('../views/MarketCompView.vue'),
        },
        {
            path: '/whisper',
            name: 'whisper',
            component: () => import('../views/WhisperView.vue'),
        },
        {
            path: '/onboard',
            name: 'onboarding',
            beforeEnter: [
                checkAuth,
                //checkNotOnboarded
            ],
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
                    path: '/profile/edit',
                    name: 'ProfileEdit',
                    component: () => import('../views/user/profile/UserEditProfileView.vue'),
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
                            meta: {
                                transition: 'slide',
                            },
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
                                    },
                                },
                                {
                                    path: 'develop/',
                                    name: 'story-develop',
                                    component: () => import('../views/story/StoryDevelopment.vue'),
                                    meta: {
                                        transition: 'slide',
                                        back: 'story'
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
                                    path: 'outline/:id',
                            name: 'outline',
                            // beforeEnter: checkAuth,
                            component: () =>
                                import('../views/OutlineView.vue'),
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
                                {
                                    path: 'market-comp/',
                                    children: [
                                        {
                                            path: ':market-comp',
                                            name: 'market-comp',
                                            component: () =>
                                                import('../views/MarketCompView.vue'),
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
            path: '/outline/:id',
            name: 'outline',
            // beforeEnter: checkAuth,
            component: () =>
                import('../views/OutlineView.vue'),
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
            redirect: to => {
                return { path: '/auth/login', query: to.query }
            },
            component: () => import('../views/AuthView.vue'),
            //beforeEnter: checkGuest,
            children: [
                {
                    path: 'login',
                    name: 'login',
                    component: () => import('../views/LoginView.vue'),
                    props: route => ({ query: route.query})
                },
                {
                    path: 'register',
                    name: 'register',
                    component: () => import('../views/RegisterView.vue'),
                    props: route => ({ query: route.query})
                }
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

        // New registration route
        {
            path: '/sign-on',
            name: 'sign-on',
            component: () => import('../views/user/auth/UserSignOnView.vue'),
        },

    ],
})

router.beforeEach(preserveQueryParams)

router.beforeEach((to, from, next) => {

    // for debugging routes
    // console.log('Navigating to:', to.path)
    // console.log('From:', from.path)
    // console.log('Auth status:', useAuthStore().isLoggedIn)

    // scroll to top on route change
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    })
    next()
})

export default router
