<template>
    <div
        class="bg-slate-100 rounded-lg w-full p-4 flex flex-col gap-3 relative shadow-lg border-slate-200"
    >
        <button
            class="absolute right-1 top-1 text-slate-600"
            @click="emit('close')"
        >
            <xmark-icon />
        </button>
        <div class="flex items-center gap-3">
            <div
                class="rounded-full w-10 h-10 shrink-0 flex items-center justify-center bg-red-600"
            >
                <microphone-icon class="text-pure-white w-4 h-4" />
            </div>

            <div class="flex flex-col gap-1">
                <span class="text-xs text-slate-400 font-bold">
                    Ready to Jump Right in?
                </span>
                <h6 class="text-base text-slate-800 font-bold">
                    Start developing now
                </h6>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import XmarkIcon from '@/components/icons/XmarkIcon.vue'
import MicrophoneIcon from '@/components/icons/MicrophoneIcon.vue'

const emit = defineEmits(['close'])
</script>

<style scoped></style>
