<template>
    <page-navigation-layout>
        <conversation-engine
            endpoint="/api/v1/conversation/stories"
            :identifier="storyId"
            @extract="onExtract"
            @finish="onFinish"
        >
            <template #header>
                <header v-if="story" class="flex w-full items-start justify-between gap-2">
                    <div
                        class="h-11 w-11 shrink-0 rounded-lg bg-gray-400"
                    ></div>
                    <staggered-text-animation
                        v-memo="story.name"
                        :texts="[
                            {
                                modelValue: story.name,
                                class: 'line-clamp-1 whitespace-nowrap grow text-start font-fjalla text-xl',
                                is: 'h3',
                            },
                        ]"
                    />
                    <v-button class="shrink-0" @click="api.post(`/api/v1/conversation/stories/finish`, {
                        identifier: storyId,
                    })">Finish</v-button>
                </header>
            </template>
        </conversation-engine>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import PageProfileLayout from '@/components/PageNavigationLayout.vue'
import ConversationEngine from '@/views/ConversationEngine.vue'
import VButton from '@/components/VButton.vue'
import { computed, ref } from 'vue'
import StaggeredTextAnimation from '@/components/StaggeredTextAnimation.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import { useRoute } from 'vue-router'
import { useStories, useStory } from '@/composables/query/story'
import axios from 'axios'
import api from '@/utils/api'

const route = useRoute()
const storyId = computed(() => +route.params.story)

const { data:story } = useStory(storyId.value)

function onFinish() {
    console.log('finish')
}

function onExtract(data: any) {}
</script>

<style scoped></style>
