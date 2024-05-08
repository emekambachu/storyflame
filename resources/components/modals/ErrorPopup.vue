<template>
    <modal-popup
        primary="Continue"
        @primary="emit('close')"
    >
        <div class="flex flex-col items-center text-center gap-2">
            <!-- Voice input silent error -->
            <svg
                class="h-24 w-auto"
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path d="M2 10v3" />
                <path d="M6 6v11" />
                <path d="M10 3v18" />
                <path d="M14 8v7" />
                <path d="M18 5v13" />
                <path d="M22 10v3" />
            </svg>

            <h1 class="font-bold text-2xl">
                We couldn't hear you. Please try again.
            </h1>
            <p>Make sure your microphone is connected and working properly.</p>
        </div>
    </modal-popup>
</template>

<script lang="ts" setup>
import ModalPopup from '@/components/modals/ModalPopup.vue'

const emit = defineEmits(['close'])
</script>

<style scoped></style>
