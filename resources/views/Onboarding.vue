<template>
    <conversation-engine
        endpoint="/api/v1/conversation/onboarding"
        @finish="onFinish"
    />
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { SuccessResponse } from '@/types/responses'
import { ChatMessage } from '@/types/chatMessage'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import ConversationEngine from '@/views/ConversationEngine.vue'
import User from '@/types/user'
import useModal from '@/composables/useModal'

const router = useRouter()
const loading = ref(true)
const auth = useAuthStore()
const message = ref(null)
const progress = ref(0)
const modal = useModal()

function extract(formData: FormData) {

}

function onFinish() {
    const modalId = modal.show('full-screen-loader', {})
    axios
        .get<SuccessResponse<User>>('/api/v1/onboarding/summary')
        .then((res) => {
            auth.updateUser(res.data.data)
            router.push({ name: 'profile' })
            close(modalId)
        })
}

onMounted(() => {
    if (!auth.user) {
        router.push({ name: 'register' })
    }
})
</script>
