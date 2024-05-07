<template>
    <div
        class="w-full min-h-dvh flex flex-col items-center justify-center gap-5"
    >
        <h1 class="text-2xl">
            Welcome to
            <span class="text-gradient font-bold">
                {{ appName }}
            </span>
        </h1>
        <div class="flex flex-col gap-2">
            <router-link
                is="btn"
                :to="{ name: 'login' }"
                class="bg-red-500 text-white px-3 py-1 rounded-full"
            >
                Get Started
            </router-link>
            <button
                v-if="isLoggedIn"
                class="text-gray-500 px-3 py-1 rounded-full"
                @click="logout"
            >
                logout
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth'

const appName = import.meta.env.VITE_APP_NAME

const { logout, isLoggedIn } = useAuthStore()
</script>
