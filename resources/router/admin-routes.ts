import {
    createRouter,
    createWebHistory,
} from 'vue-router'

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
            children: [
                {
                    path: 'achievements',
                    name: 'admin-achievements',
                    component: () => import('../views/admin/pages/achievements/AdminAchievements.vue'),
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
