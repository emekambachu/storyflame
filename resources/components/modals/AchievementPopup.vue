<template>
    <modal-popup
        primary="Continue"
        secondary="Close"
    >
        <div class="flex flex-col items-center text-center gap-2">
            <img
                :src="icon"
                alt="achievement icon"
                class="h-40 w-auto object-contain object-center"
            />
            <h1 class="font-bold text-2xl">{{ title }}</h1>
            <p>{{ description }}</p>
        </div>
    </modal-popup>
</template>

<script lang="ts" setup>
import ModalPopup from '@/components/modals/ModalPopup.vue'

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
})
</script>

<style scoped></style>
