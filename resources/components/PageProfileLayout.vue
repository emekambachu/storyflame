<template>
    <div class="bg-white flex flex-col grow">
        <header class="sticky -top-[1px] bg-white bg-opacity-75 py-3 backdrop-blur">
            <button
                class="capitalize flex items-center text-lg"
                @click="goBack"
            >
                <chevron-icon class="h-7" />
                {{ backRouteTitle }}
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

const router = useRouter()
const { setTransition } = usePagesStore()

const backRoute = computed(() => {
    return router
        .getRoutes()
        .find((route) => route.name === router.currentRoute.value.meta.back)
})

const backRouteTitle = computed(() => {
    return backRoute.value?.meta.title ?? backRoute.value?.name ?? 'Back'
})

function goBack() {
    setTransition('back')
    router.push({
        name: backRoute.value?.name ?? 'home',
    })
}
</script>

<style scoped></style>
