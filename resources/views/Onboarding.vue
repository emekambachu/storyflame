<template>
    <split-view-layout>
        <conversation-engine
            title="Brief Onboarding"
            endpoint="/api/v1/conversation/onboarding"
            @finish="onFinish"
        />
    </split-view-layout>
</template>

<script lang="ts" setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import ConversationEngine from '@/views/ConversationEngine.vue'
import useModal from '@/composables/useModal'
import { getOnboardingSummary } from '@/utils/endpoints'
import SplitViewLayout from "@/layouts/splitViewLayout.vue";

const router = useRouter()
const auth = useAuthStore()
const modal = useModal()

function onFinish() {
    // const modalId = modal.show('full-screen-loader', {})
    router.push({ name: 'profile' })
    // getOnboardingSummary().then((res) => {
    //     auth.updateUser(res.data)
    //     close(modalId)
    // })
}

onMounted(() => {
    if (!auth.user) {
        router.push({ name: 'register' })
    }
})
</script>
