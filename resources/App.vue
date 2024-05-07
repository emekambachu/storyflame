<template>
    <!-- <nav class="bg-white shadow-lg text-slate-500 p-4">nav</nav> -->
    <main class="bg-white min-h-screen">
        <router-view />
    </main>
    <!-- <footer class="bg-white shadow-lg text-slate-500 p-4">footer</footer> -->
</template>

<script lang="ts" setup>
import { useEcho } from '@/types/useEcho'
import { useAuthStore } from '@/stores/auth'
import { inject, onMounted } from 'vue'
import AchievementPopup from '@/components/modals/AchievementPopup.vue'

const { echo } = useEcho()
const { user } = useAuthStore()
const { show } = inject('modal')

echo.private(`App.Models.User.${user?.id}`).listen(
    '.achievement.unlocked',
    (e) => {
        console.log(e)
        show(AchievementPopup, {
            title: e.title,
            description: e.description,
            icon: e.icon,
        })
    }
)
</script>
