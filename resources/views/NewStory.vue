<template>
    <page-profile-layout>
        <conversation-engine
            endpoint="/api/v1/conversation/stories"
            @finish="onFinish"
        />
    </page-profile-layout>
</template>

<script lang="ts" setup>
import { useRouter } from 'vue-router'
import PageProfileLayout from '@/components/PageProfileLayout.vue'
import ConversationEngine from '@/views/ConversationEngine.vue'
import { onMounted, ref } from 'vue'
import axios from 'axios'

const router = useRouter()
const loading = ref(true)
const message = ref(null)
const progress = ref(0)

function onExtract(formData) {
    console.log(formData)
}

function onFinish() {
    console.log('finish')
}

onMounted(() => {
    loading.value = true
    axios
        .post(`/api/v1/stories`)
        .then((res) => {
            message.value = res.data.data.question
            progress.value = res.data.data.progress
        })
        .finally(() => {
            loading.value = false
        })
})
</script>

<style scoped></style>
