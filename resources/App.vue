<template>
    <!-- <nav class="bg-white shadow-lg text-slate-500 p-4">nav</nav> -->
    <main class="bg-white min-h-screen">
        <router-view />
    </main>
    <!-- <footer class="bg-white shadow-lg text-slate-500 p-4">footer</footer> -->
</template>

<script lang="ts" setup>
import { onMounted, provide, reactive, watch } from 'vue'
import { uaInjectKey } from '@/types/inject'
import { useAuthStore } from '@/stores/auth'
import { useEcho } from '@/types/useEcho'
import useModal from '@/composables/useModal'
import AchievementPopup from '@/components/modals/AchievementPopup.vue'

const authStore = useAuthStore()
const { echo } = useEcho()
const { show } = useModal()
watch(
    () => authStore.user,
    (value, oldValue, onCleanup) => {
        // only join the channel if the user has changed
        if (!!value !== !!oldValue) {
            if (authStore.user) {
                console.log('joining channel')
                echo.private(`App.Models.User.${authStore.user.id}`).listen(
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
            } else {
                echo.leaveAllChannels()
            }
        }
    },
    { immediate: true }
)
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
