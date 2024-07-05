<template>
    <main
        class="relative flex h-full grow flex-col items-center justify-between px-4 pb-10 pt-8 text-center"
    >
        <div class="flex flex-col items-center w-full">
            <slot name="header">
                <a
                    class="mb-8"
                    href="/"
                >
                    <img
                        alt="logo"
                        class="w-[393px]"
                        src="@/assets/logo.svg"
                    />
                </a>
                <aside class="flex w-full flex-col">
                    <!--            <h2 class="mb-2 text-base font-normal text-neutral-950 opacity-60">-->
                    <!--                {{ question?.title }}-->
                    <!--            </h2>-->
                    <div class="mb-14 flex items-center">
                        <progress-bar
                            :percent="Math.min(progress, 100)"
                            class="w-full"
                        />
                    </div>
                </aside>
            </slot>
        </div>
        <div class="mt-32 grow">
            <staggered-text-animation
                v-if="question"
                v-memo="[question]"
                :texts="[
                    {
                        modelValue: question?.title,
                        class: 'text-neutral-950 opacity-60 text-sm font-normal mb-2',
                        is: 'h3',
                        speed: 10,
                    },
                    {
                        modelValue: question?.content,
                        class: 'text-neutral-700 text-3xl font-bold',
                        is: 'h1',
                        speed: 20,
                    },
                ]"
            />
        </div>
        <main class="flex h-full w-full flex-col items-center">
            <template v-if="isSpeakingMode || loading">
                <div class="mb-8 mt-auto flex w-full flex-col gap-4">
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
                <div class="my-6 flex w-full grow flex-col items-start gap-4">
                    <textarea
                        v-if="question?.type === 'text'"
                        v-model="testInput"
                        class="mx-auto my-auto w-full rounded-full !border-0 bg-white text-center text-2xl font-normal text-black !outline-0 ring-0"
                        enterkeyhint="done"
                        inputmode="text"
                        placeholder="Enter your answer"
                        @keydown.enter="extractData(testInput)"
                    />
                    <template v-else>
                        <list-option
                            v-for="option in question?.options"
                            :key="option"
                            :status="selectedOptions.includes(option)"
                            class="w-full"
                            multiple
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
                        </list-option>
                    </template>
                </div>
            </template>
            <div
                class="mt-auto flex w-full flex-col items-center justify-center gap-3"
            >
                <button
                    v-if="!isSpeakingMode && !ua.isMobile"
                    :class="[
                        selectedOptions.length || testInput.length
                            ? 'bg-red-600 text-white'
                            : 'border-gray-200 text-gray-400',
                    ]"
                    class="w-full max-w-sm rounded-full border-2 p-6 font-bold transition-colors duration-75"
                    @click="
                        extractData(
                            selectedOptions.length ? selectedOptions : testInput
                        )
                    "
                >
                    Submit
                </button>
                <button
                    class="mb-0 text-base font-normal text-neutral-500"
                    @click="isSpeakingMode = !isSpeakingMode"
                >
                    Switch to
                    <span class="font-bold text-red-600">
                        {{ isSpeakingMode ? 'typing' : 'speaking' }}
                    </span>
                    mode
                </button>
            </div>
        </main>
    </main>
</template>
<script lang="ts" setup>
import { inject, onMounted, ref } from 'vue'
import { uaInjectKey } from '@/types/inject'
import useModal from '@/composables/useModal'
import StaggeredTextAnimation from '@/components/StaggeredTextAnimation.vue'
import VoiceButton from '@/components/VoiceButton.vue'
import ListOption from '@/components/ListOption.vue'
import ProgressBar from '@/components/ProgressBar.vue'
import { ChatMessage } from '@/types/chatMessage'
import api from '@/utils/api'
import axios from 'axios'

const emit = defineEmits(['extract', 'finish'])

const props = defineProps({
    endpoint: {
        type: String,
        required: true,
    },
    identifier: {
        type: Number,
        default: null,
    },
})

const testInput = ref('')
const isSpeakingMode = ref(true)
const showTooltip = ref(true)
const selectedOptions = ref<string[]>([])

const ua = inject(uaInjectKey)
const { show } = useModal()

// watch(
//     () => props.message,
//     (newQuestion) => {
//         if (props.message.type === 'system') {
//             switch (props.message.content) {
//                 case 'prompt_repeat':
//                     show(
//                         'repeat-answer',
//                         {},
//                         {
//                             onPrimary: (payload, close) => close(),
//                             onSecondary: (payload, close) => {
//                                 isSpeakingMode.value = false
//                                 close()
//                             },
//                         }
//                     )
//                     break
//                 case 'finish':
//                     emit('finish')
//                     break
//             }
//         } else if (newQuestion?.type === 'multiple_choice') {
//             isSpeakingMode.value = false
//         }
//     }
// )

const _identifier = ref<string | null>(props.identifier ?? null)
const loading = ref(true)
const question = ref<ChatMessage | null>(null)
const progress = ref(0)

function setMessage(message: ChatMessage) {
    question.value = message
    if (message.type === 'system' && message.content === 'finish') {
        emit('finish')
    }
}

type ConversationData = {
    identifier: string
    question: ChatMessage
    progress: number,
    data: any
}

function extractData(answer: string | string[] | Blob) {
    console.log(answer)
    const formData = new FormData()
    console.log('identifier', _identifier.value)
    formData.append('identifier', ''+_identifier.value)
    console.log(_identifier.value)
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

    console.log(formData)
    // return
    loading.value = true
    api.post<ConversationData>(props.endpoint, formData)
        .then((response) => {
            console.log(response.data)
            _identifier.value = response.data.identifier
            progress.value = response.data.progress
            setMessage(response.data.question)
            emit('extract', response.data.data)
        })
        .finally(() => {
            loading.value = false
        })
}

onMounted(() => {
    loading.value = true
    console.log('identifier', _identifier.value)
    api.get<ConversationData>(props.endpoint, {
        params: {
            identifier: _identifier.value ?? null,
        },
    })
        .then((response) => {
            _identifier.value = response.data.identifier
            console.log(_identifier.value)
            progress.value = response.data.progress
            setMessage(response.data.question)
        })
        .finally(() => {
            loading.value = false
        })
})
</script>
