<template>
    <div class="p-3 flex items-center gap-3 bg-cyan-50 rounded-lg"
    style="box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.15);">

        <div class="flex flex-col gap-1 w-full">
            <p v-if="card.story || card.episode" class="flex items-center gap-1 text-xs text-stone-600">
                <span>{{truncateTitle(card.story, 21)}}</span>
                <point-icon v-if="card.story && card.episode"/>
                <span>{{truncateTitle(card.episode, 21)}}</span>

            </p>
            <h5 class="text-stone-800 text-base font-semibold">
                {{ card.title }}
            </h5>

            <p class="text-stone-600 text-xs font-normal">
                {{ card.title }}
            </p>
        </div>

        <button>
            <microphone-icon class="text-stone-600 w-4 h-4 shrink-0"/>
        </button>
    </div>
</template>

<script setup lang="ts">
import PointIcon from "@/components/icons/PointIcon.vue"
import MicrophoneIcon from "@/components/icons/MicrophoneIcon.vue"

const props = defineProps({
    card: {
        type: Object,
        required: true,
    }
})

function truncateTitle(title: string, maxLength: number): string {
  return title.length > maxLength
    ? title.substring(0, maxLength - 3) + '...'
    : title
}
</script>

<style scoped>

</style>