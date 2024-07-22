<template>
    <page-navigation-layout>
        <conversation-engine
            :identifier="storyID"
            :achievement="achievementID"
            :endpoint="`/api/v1/conversation/${props.endpoint}`"
        />
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import ConversationEngine from '@/views/ConversationEngine.vue'
import { useRoute } from 'vue-router'
import { computed } from 'vue'

const props = defineProps({
    endpoint: { type: String, required: true },
    element: { type: String, required: true },
    story: { type: Number, required: true },
})

const route = useRoute()
const storyID = computed(() => +route.params[props.element])
const achievementID = computed(() => +route.params.achievement)
</script>

<style scoped></style>
