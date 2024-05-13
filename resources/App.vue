<template>
    <!-- <nav class="bg-white shadow-lg text-slate-500 p-4">nav</nav> -->
    <main class="bg-white min-h-screen">
        <router-view />
    </main>
    <!-- <footer class="bg-white shadow-lg text-slate-500 p-4">footer</footer> -->
</template>

<script lang="ts" setup>
import { onMounted, provide, reactive } from 'vue'
import { uaInjectKey } from '@/types/inject'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const ua = reactive({
    agent: '',
    isMobile: false,
})

provide(uaInjectKey, ua)

onMounted(() => {
    ua.agent = navigator.userAgent
    ua.isMobile = /Mobi/.test(ua.agent)

    authStore.getUser()
})
</script>
