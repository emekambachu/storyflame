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
        <div class="flex gap-2">
            <router-link
                is="btn"
                :to="{ name: 'login' }"
                class="bg-red-500 text-white px-3 py-1 rounded-full"
            >
                Get Started
            </router-link>
        </div>
    </div>
</template>

<script lang="ts" setup>
const appName = import.meta.env.VITE_APP_NAME
</script>
