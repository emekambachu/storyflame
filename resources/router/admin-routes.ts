import {
    createRouter,
    createWebHistory,
} from 'vue-router'
import routeService from '@/utils/route-service'

const router = createRouter({
    history: createWebHistory(),
    routes: [

        {
            path: '/admin/login',
            name: 'admin-login',
            component: () => import('../views/admin/pages/AdminLoginView.vue'),
        },

        {
            path: '/admin',
            component: () => import('../views/admin/layouts/AdminLayout.vue'),
            beforeEnter: (to, from, next) => {
                routeService.authenticateUser(
                    '/api/admin/authenticate',
                    next,
                    '/admin/login',
                )
            },
            children: [
                {
                    path: 'achievements',
                    name: 'admin-achievements',
                    component: () => import('../views/admin/pages/achievements/AdminAchievements.vue'),
                },
                {
                    path: 'data-points',
                    name: 'admin-data-points',
                    component: () => import('../views/admin/pages/data-points/AdminDataPoints.vue'),
                },
                {
                    path: 'summaries',
                    name: 'admin-summaries',
                    component: () => import('../views/admin/pages/summaries/AdminSummaries.vue'),
                },
                {
                    path: 'llm-prompts',
                    name: 'admin-llm-prompts',
                    component: () => import('../views/admin/pages/llm-prompts/AdminLlmPromptsView.vue'),
                },
            ],
        },

    ],
});

router.beforeEach((to, from, next) => {
    // scroll to top on route change
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    })
    next()
})

export default router
