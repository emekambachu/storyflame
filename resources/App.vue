<template>
    <div class="w-full flex flex-col gap-3 bg-gray-100">
        <nav
            v-if="showNav"
            class="w-full flex flex-row h-14 items-center px-2 lg:px-4 text-2xs text-stone-500 justify-between bg-stone-50 border-b border-solid border-stone-200 z-20"
            style="position:sticky; top: 0;"
        >
            <div>
<!--                Click on the logo-icon should take the user to "/" -->
                <router-link :to="{ name: 'home' }">
                    <logo-icon class="w-8 h-8" />
                </router-link>
            </div>
            <div class="">
                {{ $route.meta.title }}
            </div>
            <div>
                <router-link :to="{ name: 'profile' }" class="flex h-8 w-8 shrink-0 rounded-full bg-stone-200">
                    <UserAvatar />
                </router-link>
            </div>
        </nav>
        <main class="w-full flex flex-col mx-auto items-center justify-start min-h-dvh ">
            <router-view v-slot="{ Component, route }">
<!--                <transition-->
<!--                    :mode="transition.mode"-->
<!--                    :name="transition.name"-->
<!--                    @after-leave="setTransition('none')"-->
<!--                >-->
                    <component
                        :is="Component"
                        :key="route.fullPath"
                        :class="[
                            'bg-white',
                            useFullWidth ? 'w-full' : 'max-w-screen-md'
                        ]"
                    />
<!--                </transition>-->
            </router-view>
        </main>
    </div>
    <!-- <footer class="bg-white shadow-lg text-slate-500 p-4">footer</footer> -->
</template>

<script lang="ts" setup>
import { onMounted, provide, reactive, watch, ref } from 'vue'
import { uaInjectKey } from '@/types/inject'
import { useAuthStore } from '@/stores/auth'
import { useEcho } from '@/types/useEcho'
import useModal from '@/composables/useModal'
import AchievementPopup from '@/components/modals/AchievementPopup.vue'
import { usePagesStore } from '@/stores/pages'
import { useRouter } from 'vue-router'
import MockPopup from '@/components/MockPopup.vue'
import LogoIcon from '@/components/icons/LogoIcon.vue'
import UserAvatar from "@/components/UserAvatar.vue";


const authStore = useAuthStore()
const { echo } = useEcho()
const { show } = useModal()
const { transition, setTransition } = usePagesStore()

const router = useRouter()

const showNav = ref(true)
const useFullWidth = ref(false)

provide('showNav', showNav)
provide('useFullWidth', useFullWidth)

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

// const mockStore = useMockStore()

// axios.interceptors.request.use((config) => {
//     if (mockStore.isActive(config.method, config.url)) {
//         console.log('Mocking request', config)
//     } else {
//         console.log(config.method, config.url)
//     }
//     return config
// })

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
    z-index: 20;
    transform: translateX(100%);
}

.slide-enter-to {
    z-index: 20;
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
    z-index: 20;
    transform: translateX(0%);
}

.slide-back-leave-to {
    z-index: 20;
    transform: translateX(100%);
}
</style>
