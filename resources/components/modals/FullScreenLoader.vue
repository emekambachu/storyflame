<template>
    <div class="flex flex-col items-center bg-white py-10">
        <div class="flex flex-col items-center mb-[-129px]">
            <logo-icon />

            <h1 class="text-black text-2xl font-bold">
                Story
                <span class="text-orange-500 -ml-1">Flame</span>
            </h1>
        </div>

        <div class="mx-auto my-auto flex flex-col items-center gap-2">
            <app-loader class="h-8" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import LogoIcon from '@/components/icons/LogoIcon.vue'
import AppLoader from '@/components/AppLoader.vue'

const emit = defineEmits(['close'])

defineProps({
    type: {
        type: String,
        default: 'full',
    },
})
</script>

<style scoped></style>
