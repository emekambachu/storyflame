<template>
    <div class="flex w-full max-w-full items-center gap-3 overflow-scroll">
        <div
            v-for="(item, itemID) in createNew"
            :key="itemID"
            class="flex w-full flex-col items-center gap-1 text-center"
        >
            <div
                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-stone-100 font-normal"
            >
                <component
                    :is="item?.icon"
                    class="text-stone-400"
                />
            </div>
            <span class="text-xs font-semibold text-stone-500">
                {{ item.title }}
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
import SeriesIcon from '@/components/icons/SeriesIcon.vue'
import BookWithPlus from '@/components/icons/BookWithPlus.vue'
import UserWithPlus from '@/components/icons/UserWithPlus.vue'
import SequencesWithPlus from '@/components/icons/SequencesWithPlus.vue'
const createNew = [
    { icon: SeriesIcon, title: 'Series' },
    { icon: BookWithPlus, title: 'Story' },
    { icon: UserWithPlus, title: 'Character' },
    { icon: SequencesWithPlus, title: 'Sequence' },
]
</script>

<style scoped></style>
