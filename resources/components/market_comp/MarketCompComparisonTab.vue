<template>
    <div class="flex flex-col gap-2 bg-stone-100 pt-2">
        <title-section
            title="Plot"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.plot"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :list="item?.list"
                :lists="item?.lists"
                :title-class="
                    itemId == data.plot?.length - 1
                        ? 'text-stone-900 text-sm font-bold'
                        : 'text-stone-600 text-sm font-bold'
                "
                description-class="text-zinc-800 text-sm"
                list-class="text-zinc-800 text-sm"
            />
        </title-section>
        <title-section
            title="Characters"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.characters"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :list="item?.list"
                :lists="item?.lists"
                :title-class="
                    itemId == data.characters?.length - 1
                        ? 'text-stone-900 text-sm font-bold'
                        : 'text-stone-600 text-sm font-bold'
                "
                description-class="text-zinc-800 text-sm"
                list-class="text-zinc-800 text-sm"
            />
        </title-section>
        <title-section
            title="Themes"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.themes"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :list="item?.list"
                :lists="item?.lists"
                :title-class="
                    itemId == data.themes?.length - 1
                        ? 'text-stone-900 text-sm font-bold'
                        : 'text-stone-600 text-sm font-bold'
                "
                description-class="text-zinc-800 text-sm"
                list-class="text-zinc-800 text-sm"
            />
        </title-section>

        <title-section
            title="World"
            class="bg-white px-3 py-5"
            title-class="text-lg text-black font-bold"
        >
            <subtitle-section
                v-for="(item, itemId) in data.world"
                :key="itemId"
                :title="item.title"
                :description="item?.description"
                :list="item?.list"
                :lists="item?.lists"
                :title-class="
                    itemId == data.world?.length - 1
                        ? 'text-stone-900 text-sm font-bold'
                        : 'text-stone-600 text-sm font-bold'
                "
                description-class="text-zinc-800 text-sm"
                list-class="text-zinc-800 text-sm"
            />
        </title-section>
    </div>
</template>

<script setup lang="ts">
import TitleSection from '@/components/TitleSection.vue'
import SubtitleSection from '@/components/cards/SubtitleSection.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
