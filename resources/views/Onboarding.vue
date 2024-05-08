<template>
    <div
        class="flex flex-col items-center text-center px-4 relative pt-16 pb-10 min-h-screen"
    >
        <aside class="flex flex-col w-full">
            <h2 class="text-neutral-950 opacity-60 text-base font-normal mb-2">
                {{ question?.title }}
            </h2>
            <div class="flex items-center mb-14">
                <progress-bar
                    :percent="Math.min(progress, 100)"
                    class="w-full"
                />
            </div>
        </aside>
        <main
            class="grow flex flex-col items-center h-full max-h-[432px] w-full"
        >
            <staggered-animation
                v-if="question"
                v-memo="[question]"
                :texts="[
                    {
                        modelValue: question?.subtitle,
                        class: 'text-neutral-950 opacity-60 text-sm font-normal mb-2',
                        is: 'h3',
                    },
                    {
                        modelValue: question?.content,
                        class: 'text-neutral-700 text-3xl font-bold',
                        is: 'h1',
                    },
                ]"
            />

            <template v-if="isSpeakingMode || loading">
                <div class="w-full mb-8 mt-auto flex flex-col gap-4">
                    <!--        <tooltip-message-->
                    <!--          v-if="!showTooltip"-->
                    <!--          class="text-red-200 w-fit mx-auto mb-6"-->
                    <!--          message-class="bg-red-200 px-3 py-2 text-center w-full rounded-lg text-red-600 text-xs font-normal min-h-9"-->
                    <!--        >-->
                    <!--          <template #text>-->
                    <!--            <p class="text-red-600 flex items-center gap-2">-->
                    <!--              <img src="../assets//images/time.png" />-->
                    <!--              Hurry up! Only 30sec left!-->
                    <!--            </p>-->
                    <!--          </template>-->
                    <!--        </tooltip-message>-->
                    <voice-button
                        :loading="loading"
                        class="mx-auto"
                        @click="showTooltip = !showTooltip"
                        @recorded="extractData"
                    />
                </div>
            </template>
            <template v-else>
                <textarea
                    v-if="question?.type === 'text'"
                    v-model="testInput"
                    class="mx-auto my-auto w-full text-center text-2xl text-black font-normal bg-white !border-0 !outline-0"
                    placeholder="Enter your answer"
                    @keydown.enter="extractData(testInput)"
                />
                <div
                    v-else
                    class="w-full my-6 flex flex-col items-start gap-4"
                >
                    <ListOption
                        v-for="option in question?.options"
                        :key="option"
                        :status="selectedOptions.includes(option)"
                        class="w-full"
                        @click="
                            selectedOptions.includes(option)
                                ? selectedOptions.splice(
                                      selectedOptions.indexOf(option),
                                      1
                                  )
                                : selectedOptions.push(option)
                        "
                    >
                        <template #text>{{ option }}</template>
                    </ListOption>
                    <button
                        :class="[
                            selectedOptions.length
                                ? 'bg-red-600 text-white'
                                : 'text-gray-400 border-gray-200',
                        ]"
                        class="w-full p-6 border-2 rounded-full duration-75 font-bold"
                        @click="extractData(selectedOptions)"
                    >
                        Submit
                    </button>
                </div>
            </template>
        </main>

        <div class="flex justify-center mt-auto w-full">
            <button
                class="text-neutral-500 text-base font-normal mb-0"
                @click="isSpeakingMode = !isSpeakingMode"
            >
                Switch to
                <span class="text-red-600 font-bold">
                    {{ isSpeakingMode ? 'typing' : 'speaking' }}
                </span>
                mode
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue'
import ListOption from '@/components/ListOption.vue'
import VoiceButton from '@/components/VoiceButton.vue'
import ProgressBar from '@/components/ProgressBar.vue'
import axios from 'axios'
import { SuccessResponse } from '../types/responses'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { ChatMessage } from '@/types/chatMessage'
import StaggeredAnimation from '@/components/StaggeredTextAnimation.vue'

const router = useRouter()

const showRepeatAnswerModal = ref(false)
const testInput = ref('')
const isSpeakingMode = ref(true)
const showTooltip = ref(true)
const question = ref<ChatMessage | null>(null)
const progress = ref(0)
const loading = ref(true)
const selectedOptions = ref<string[]>([])

function checkIfFinished() {
    // if (messages.value.filter((message) => message.type === 'finish').length) {
    // 	router.push('/summary')
    // }
}

watch(question, (newQuestion) => {
    if (newQuestion?.type === 'multiple_choice') {
        isSpeakingMode.value = false
    }
})

function setMessage(message: ChatMessage) {
    if (message.type === 'system') {
        if (message.content === 'finish') {
            router.push('/summary')
        }
    } else {
        question.value = message
    }
}

function extractData(answer: string | string[] | Blob) {
    loading.value = true

    console.log(answer)
    const formData = new FormData()
    if (Array.isArray(answer)) {
        selectedOptions.value.forEach((option) => {
            formData.append('options[]', option)
        })
    } else {
        let key
        if (typeof answer === 'string') {
            key = 'message'
        } else {
            key = 'audio'
        }
        formData.append(key, answer)
    }

    testInput.value = ''
    selectedOptions.value = []

    axios
        .post<
            SuccessResponse<{
                message: ChatMessage
                progress: number
            }>
        >('/api/v1/onboarding', formData)
        .then((response) => {
            setMessage(response.data.data.message)
            progress.value = response.data.data.progress
        })
        .finally(() => {
            loading.value = false
        })
}

const auth = useAuthStore()

onMounted(() => {
    loading.value = true
    if (!auth.user) {
        router.push({ name: 'register' })
    }
    checkIfFinished()
    setTimeout(() => {
        axios
            .get<
                SuccessResponse<{
                    questions_count: number
                    question: ChatMessage
                    progress: number
                }>
            >('/api/v1/onboarding')
            .then((response) => {
                setMessage(response.data.data.question)
                progress.value = response.data.data.progress
            })
            .finally(() => {
                loading.value = false
            })
    }, 1000)
})
</script>
