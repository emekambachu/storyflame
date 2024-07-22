<template>
    <div class="flex flex-col gap-2 rounded-lg bg-stone-100 p-3">
        <div class="flex w-full items-start justify-between">
            <div class="flex flex-col gap-0.5">
                <p
                    v-if="element?.story || element?.episode"
                    class="flex items-center gap-1 text-xs font-medium text-stone-500"
                >
                    {{ truncateTitle(element?.episode, 21) }}
                    <point-icon v-if="element?.story && element?.episode" />
                    {{ truncateTitle(element?.story, 21) }}
                </p>

                <h5 class="text-base font-bold text-stone-800">
                    {{ element.title }}
                </h5>

                <p
                    v-if="element?.role || element?.type"
                    class="flex items-center gap-1 text-xs font-medium text-stone-600"
                >
                    {{ element?.role }}
                    <point-icon v-if="element?.role && element?.type" />
                    {{ element?.type }}
                </p>
            </div>

            <flame-icon
                v-if="element?.progress"
                :progress="element.progress"
                class="!h-8 !w-8"
                flameClass="w-6 h-6"
            />
        </div>
        <hr
            v-if="showLine"
            class="w-full text-stone-500"
        />
        <p
            v-if="element?.description"
            class="text-sm text-stone-600"
        >
            {{ element.description }}
        </p>
    </div>
</template>

<script setup lang="ts">
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

const props = defineProps({
    element: {
        type: Object,
        required: true,
    },
    showLine: {
        type: Boolean,
        default: true,
    },
})

function truncateTitle(title: string, maxLength: number): string {
    if (title) {
        return title.length > maxLength
            ? title.substring(0, maxLength - 3) + '...'
            : title
    } else {
        return ''
    }
}
</script>

<style scoped></style>
