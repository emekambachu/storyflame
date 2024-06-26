<template>
    <div class="flex w-full items-center gap-2 rounded-lg bg-stone-100 p-2">
        <image-component
            :src="card?.image?.path"
            alt="story"
            class="h-[72px] w-[72px] shrink-0 rounded-lg object-cover"
        />

        <div class="flex w-full flex-col gap-1">
            <div class="flex w-full items-center justify-between gap-1">
                <h4
                    class="w-full font-fjalla text-base text-xl font-bold text-stone-950"
                >
                    {{ truncateTitle(card.title, 23) }}
                </h4>

                <flame-icon
                    :progress="card.progress"
                    flame-class="w-6 h-6"
                />
            </div>

            <p
                class="flex items-center gap-1 text-sm font-normal text-stone-500"
            >
                {{ card?.episodes ?? 0 }} Episodes
                <point-icon />
                {{ card?.characters ?? 0 }} Characters
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

import ImageComponent from '@/components/ImageComponent.vue'

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})

function truncateTitle(title: string | undefined, maxLength: number): string {
    if (!title) return ''
    return title.length > maxLength
        ? title.substring(0, maxLength - 3) + '...'
        : title
}
</script>

<style scoped></style>
