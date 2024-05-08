<template>
    <div class="h-fit flex flex-col items-center">
        <div
            :class="messageClass"
            style="
                box-shadow:
                    0px 0px 4px 0px rgba(0, 0, 0, 0.25),
                    0px 0px 16px -4px rgba(16, 24, 40, 0.08);
            "
        >
            <slot />
        </div>
        <triangle-icon
            class="absolute text-white -bottom-2 mx-auto left-auto right-auto z-0"
        />
    </div>
</template>

<script lang="ts" setup>
import TriangleIcon from '@/components/icons/TriangleIcon.vue'

const props = defineProps({
    messageClass: {
        type: String,
        default:
            'bg-pure-white px-3 py-2 text-center w-full rounded-lg text-red-600 text-xs font-normal min-h-9',
    },
})
</script>

<style scoped></style>
