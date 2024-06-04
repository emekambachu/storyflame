<template>
    <div class="w-full px-4 flex flex-col gap-8">
        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Character Arcs</h4>
            </template>
            <text-list :items="data.associated_characters" />
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Main protagonist</h4>
            </template>
            <p class="text-slate-600 text-">{{ data.main_protagonist }}</p>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Goal:</h4>
            </template>
            <p class="text-slate-600 text-">{{ data.goal }}</p>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Twists and Turns:</h4>
            </template>
            <p class="text-slate-600 text-">{{ data.twists_turns }}</p>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Exploration:</h4>
            </template>
            <p class="text-slate-600 text-">{{ data.exploration }}</p>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">Difficulties:</h4>
            </template>
            <text-list :items="data.difficulties" />
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">
                    Conflict Resolution:
                </h4>
            </template>
            <p class="text-slate-600 text-">{{ data.resolution }}</p>
        </title-section>

        <title-section>
            <template #title>
                <h4 class="text-lg text-black font-bold">End:</h4>
            </template>
            <p class="text-slate-600 text-">{{ data.end }}</p>
        </title-section>
    </div>
</template>

<script setup lang="ts">
import TextList from '@/components/TextList.vue'
import TitleSection from '@/components/TitleSection.vue'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<style scoped></style>
