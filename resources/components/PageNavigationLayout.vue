<template>
    <div class="flex grow flex-col bg-white">
        <header
            :class="[
                fixed ? 'fixed w-full' : 'sticky',
                {
                    'bg-white bg-opacity-75 backdrop-blur': !transparent,
                },
            ]"
            class="-top-[1px] z-[15] pt-12"
        >
            <button
                class="flex items-center px-4 text-lg capitalize"
                @click="goBack"
            >
                <chevron-icon class="h-4" />
                <template v-if="!noBackText">
                    {{ backRouteTitle }}
                </template>
            </button>
        </header>
        <slot />
    </div>
</template>

<script lang="ts" setup>
import { useRouter } from 'vue-router'
import { computed } from 'vue'
import { usePagesStore } from '@/stores/pages'
import ChevronIcon from '@/components/icons/ChevronIcon.vue'

defineProps({
    fixed: {
        type: Boolean,
        default: false,
    },
    transparent: {
        type: Boolean,
        default: false,
    },
    noBackText: {
        type: Boolean,
        default: false,
    },
})

const router = useRouter()
const { setTransition } = usePagesStore()

const backRoute = computed(() => {
    if (!router.currentRoute.value.meta.back) return null
    return router
        .getRoutes()
        .find((route) => route.name === router.currentRoute.value.meta.back)
})

const backRouteTitle = computed(() => {
    return backRoute.value?.meta.title ?? backRoute.value?.name ?? 'Back'
})

function goBack() {
    setTransition('back')
    if (backRoute.value) {
        router.push({
            name: backRoute.value?.name,
        })
    } else {
        router.back()
    }
}
</script>

<style scoped></style>
