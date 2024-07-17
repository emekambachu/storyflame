<template>
    <div class="flex flex-col gap-1">
        <div class="flex items-center gap-2">
            <img
                v-if="image"
                :src="image"
                :class="imageClass"
            />
            <h6
                v-if="title"
                :class="titleClass"
            >
                {{ title }}
            </h6>
        </div>
        <p
            v-if="description"
            :class="descriptionClass"
        >
            {{ description }}
        </p>
        <template v-if="list">
            <text-list
                :items="list"
                class="pl-2"
                :list-class="listClass"
            />
        </template>
        <p
            v-if="quote"
            :class="quoteClass"
        >
            {{ quote }}
        </p>
        <template v-if="subpoints">
            <subtitle-section
                v-for="(item, itemID) in subpoints"
                :key="itemID"
                :title="item.title"
                :description="item?.description"
                title-class="text-sm text-stone-500 font-bold"
            />
        </template>

        <template v-if="lists?.length">
            <div
                v-for="(item, itemID) in lists"
                :key="itemID"
                class="pl-3"
            >
                <h6
                    v-if="item?.listTitle"
                    class="text-medium text-xs text-stone-800"
                >
                    {{ item.listTitle }}
                </h6>
                <text-list
                    v-if="item?.list"
                    :items="item.list"
                    class="pl-2"
                    :list-class="listsClass"
                />
            </div>
        </template>
        <slot />
    </div>
</template>

<script setup lang="ts">
import TextList from '@/components/TextList.vue'

const props = defineProps({
    title: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    },
    image: {
        type: String,
        default: null,
    },
    quote: {
        type: String,
        default: null,
    },
    subpoints: {
        type: Array,
        default: null,
    },
    list: {
        type: Array,
        default: null,
    },
    lists: {
        type: Array,
        default: null,
    },
    titleClass: {
        type: String,
        default: 'text-sm font-bold text-stone-900',
    },
    quoteClass: {
        type: String,
        default:
            'border-y border-solid border-stone-300 py-2 text-xs font-normal text-stone-700',
    },
    descriptionClass: {
        type: String,
        default: 'text-sm font-normal text-stone-700',
    },
    imageClass: {
        type: String,
        default: 'w-10 h-10 shrink-0 rounded',
    },
    listClass: {
        type: String,
        default: 'text-sm font-bold text-stone-700',
    },
    listsClass: {
        type: String,
        default: 'text-xs font-normal text-stone-700',
    },
})
</script>

<style scoped></style>
