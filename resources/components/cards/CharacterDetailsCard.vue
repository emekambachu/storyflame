<template>
    <section class="flex flex-col gap-3 self-stretch bg-white text-stone-700">
        <h2
            v-if="data?.section_title"
            class="w-full text-base font-bold text-stone-800"
        >
            {{ data.section_title }}
        </h2>
        <p
            v-if="data?.description"
            class="text-sm font-normal leading-[140%] text-stone-700"
        >
            {{ data.description }}
        </p>
        <dl
            v-if="data?.data?.length"
            class="flex flex-col gap-2"
        >
            <div
                v-for="(item, itemID) in data.data"
                :key="itemID"
                class="flex items-start gap-2 leading-[140%]"
            >
                <dt class="w-full max-w-[92px] text-xs font-semibold">
                    {{ item.title }}:
                </dt>
                <dd class="text-sm font-normal">
                    {{ item.value }}
                </dd>
            </div>
        </dl>
    </section>
</template>

<script setup lang="ts">
const props = defineProps({
    data: {
        type: Array,
        default: null,
    },
})
</script>
