<template>
    <div
        class="flex items-center p-2 justify-between bg-cyan-50 rounded-lg"
        style="box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.15)"
    >
        <img
            v-if="item.image.path"
            :src="item.image.path"
            class="w-8 h-8 object-contain"
        />

        <div class="flex flex-col mr-auto ml-3">
            <h5 class="text-base text-zinc-800 font-bold">{{ item.title }}</h5>
            <p class="text-xs text-gray-500">
                Provide basic information about your story
            </p>
        </div>

        <button><microphone-icon class="text-slate-400 w-4 h-4" /></button>
    </div>
</template>

<script setup lang="ts">
import MicrophoneIcon from '@/components/icons/MicrophoneIcon.vue'
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
