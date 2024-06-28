<template>
    <article
        class="flex max-w-[361px] flex-col justify-center rounded-xl text-sm"
    >
        <header class="flex flex-col justify-center">
            <h2
                v-if="card.headerTitle"
                class="text-xs font-bold uppercase text-stone-500"
            >
                {{ card.headerTitle }}
            </h2>
            <h3 class="text-ellipsis font-semibold text-stone-900">
                {{ card.title }}
            </h3>
            <p class="leading-5 text-stone-700">{{ card.description }}</p>
        </header>
        <section
            class="mt-1 flex flex-col border-t border-solid border-stone-300 text-xs leading-4 text-stone-700"
        >
            <div
                v-for="(dialogue, index) in card.dialogues"
                :key="index"
                class="flex flex-col border-b border-solid border-stone-300 py-2"
            >
                <p>{{ dialogue.speaker }}: "{{ dialogue.quote }}"</p>
                <p class="mt-2">
                    {{ dialogue.response.speaker }}: "{{
                        dialogue.response.quote
                    }}"
                </p>
            </div>
        </section>
        <footer
            class="mt-1 flex flex-col pl-3 text-xs font-medium leading-4 text-stone-800"
        >
            <h4 class="text-ellipsis">{{ card.visual.title }}</h4>
            <p class="font-normal leading-4 text-stone-700">
                {{ card.visual.description }}
            </p>
            <h4 class="mt-2 text-ellipsis">{{ card.emotional.title }}</h4>
            <p class="font-normal text-stone-700">
                {{ card.emotional.description }}
            </p>
        </footer>
    </article>
</template>

<script setup lang="ts">
const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})
</script>
