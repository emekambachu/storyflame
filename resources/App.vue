<template>
    <!-- <nav class="bg-white shadow-lg text-slate-500 p-4">nav</nav> -->
    <main class="bg-white min-h-screen flex flex-col">
        <router-view v-slot="{ Component, route }">
            <transition
                :mode="transition.mode"
                :name="transition.name"
                @after-leave="setTransition('default')"
            >
                <component
                    :is="Component"
                    :key="route.fullPath"
                    class="bg-white"
                />
            </transition>
        </router-view>
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
import { usePagesStore } from '@/stores/pages'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const { echo } = useEcho()
const { show } = useModal()
const { transition, setTransition } = usePagesStore()

const router = useRouter()

router.beforeEach((to, from, next) => {
    if (from.name === undefined && from.path === '/') setTransition('none')
    else if (!transition.name.endsWith('-back')) {
        if (to.meta.transition) {
            setTransition(to.meta.transition)
        } else {
            setTransition('default')
        }
    }
    next()
})

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

<style scoped>
.slide-enter-active,
.slide-leave-active,
.slide-back-enter-active,
.slide-back-leave-active {
    position: fixed;
    width: 100%;
    min-height: 100vh;
    top: 0;
}

.slide-enter-active {
    transition: transform 0.25s ease-in-out;
    z-index: 2;
    transform: translateX(100%);
}

.slide-enter-to {
    z-index: 2;
    transform: translateX(0);
}

/**
.slide-leave-active {
    transition: all 0.25s ease-in-out;
    z-index: 2;
}

.slide-leave-to {
    filter: brightness(0.75);
}

.slide-back-enter-active {
    transition: all 0.25s ease-in-out;
}
.slide-back-enter-from {
    filter: brightness(0.75)
}
**/

.slide-leave-to,
.slide-leave-active {
    z-index: -1;
}

.slide-back-leave-active {
    transition: transform 0.25s ease-in-out;
    z-index: 2;
    transform: translateX(0%);
}

.slide-back-leave-to {
    z-index: 2;
    transform: translateX(100%);
}
</style>
