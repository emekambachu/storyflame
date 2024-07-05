<template>
    <div
        class="flex items-center gap-2 justify-between pt-8 pb-5 px-4 pl-10"
        style="
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, #000 100%),
                linear-gradient(
                    0deg,
                    rgba(0, 0, 0, 0.3) 0%,
                    rgba(0, 0, 0, 0.3) 100%
                ),
                #404040;
        "
    >
        <img
            :src="data.image.path"
            class="w-[44px] h-[44px] rounded-lg backdrop-grayscale-[50%]"
        />

        <div class="flex flex-col gap-1">
            <h2 class="text-stone-50 text-xl font-fjalla">
                {{ data.title }}
            </h2>
            <p
                class="w-full flex items-center gap-2 text-stone-400 text-[10px]"
            >
                {{ data.story }}
                <span
                    v-if="data.episode"
                    class="flex items-center gap-2"
                >
                    <point-icon />
                    {{ data.episode }}
                </span>
            </p>
        </div>

        <div class="w-10 h-10 shrink-0">
            <progress-bar-circle
                :percent="data.percent"
                class="w-full"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import PointIcon from '@/components/icons/PointIcon.vue'
import ChevronIcon from '@/components/icons/ChevronIcon.vue'
import ProgressBarCircle from '@/components/ProgressBarCircle.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
