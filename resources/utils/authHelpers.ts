// src/utils/authHelpers.ts
import { RouteLocationNormalizedLoaded, Router } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

export const handleAuthError = async (router: Router, route: RouteLocationNormalizedLoaded) => {
    const authStore = useAuthStore();

    const query = route.query || {}; // Provide a default empty object if query is undefined

    if (authStore.isLoggedIn) {
        await authStore.logout();
        router.push({ name: 'login', query });
    } else {
        router.push({ name: 'login', query });
    }
};
