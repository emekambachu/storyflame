<template>
  <div
    class="flex flex-col items-center text-center px-4 relative pt-16 pb-10 min-h-screen"
  >
    <aside class="flex flex-col w-full">
      <h2 class="text-neutral-950 opacity-60 text-base font-normal mb-2">
        Getting to know you
      </h2>
      <div class="flex items-center mb-14">
        <progress-bar
          :percent="
            (Math.min(Object.keys(data).length, questionsCount) /
              questionsCount) *
            100
          "
          class="w-full"
        />
      </div>
    </aside>
    <app-loader v-if="loading" />
    <main
      v-else
      class="grow flex flex-col items-center h-full max-h-[432px] w-full"
    >
      <h3 class="text-neutral-950 opacity-60 text-sm font-normal mb-2">
        {{ lastQuestion.subtitle }}
      </h3>
      <h1 class="text-neutral-700 text-3xl font-bold">
        {{ lastQuestion.text }}
      </h1>

      <div
        v-if="isSpeakingMode"
        class="w-full mb-8 mt-auto flex flex-col gap-4"
      >
<!--        <tooltip-message-->
<!--          v-if="showTooltip"-->
<!--          class="w-fit mx-auto text-pure-white"-->
<!--        >-->
<!--          <template #text><p class="text-red-600">Press to start</p></template>-->
<!--        </tooltip-message>-->

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
          class="mx-auto"
          @click="showTooltip = !showTooltip"
          @transcribed="extractData"
        />
      </div>
      <template v-else>
        <div
          v-if="false"
          class="w-full my-6 flex flex-col items-start gap-4"
        >
          <ListOption class="w-full">
            <template #text>📝 Develop a story</template>
          </ListOption>

          <ListOption class="w-full">
            <template #text>🎬 Get feedback on a script</template>
          </ListOption>

          <ListOption class="w-full">
            <template #text>🦄 Other</template>
          </ListOption>
        </div>
        <template v-else>
          <textarea
            v-model="testInput"
            class="mx-auto my-auto w-full text-center text-2xl text-black font-normal bg-white !border-0 !outline-0"
            placeholder="Enter your answer"
            @keydown.enter="extractData(testInput)"
          />
        </template>
      </template>
    </main>
    <!-- <pre class="text-start">{{ data }}</pre> -->
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
      <!--            <button class="text-red-600 font-bold" @click="showRepeatAnswerModal = true">-->
      <!--                Skip-->
      <!--            </button>-->
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import ListOption from '@/components/ListOption.vue'
import VoiceButton from '@/components/VoiceButton.vue'
import ProgressBar from '@/components/ProgressBar.vue'
import TooltipMessage from '@/components/TooltipMessage.vue'
import axios from 'axios'
import { SuccessResponse } from '../types/responses'
import AppLoader from '../components/AppLoader.vue'
import { useRouter } from 'vue-router'

const showRepeatAnswerModal = ref(false)
const testInput = ref('')
const isSpeakingMode = ref(true)
const showTooltip = ref(true)
const questionsCount = ref(null)
const data = ref(
  JSON.parse(localStorage.getItem('onboarding.user') || 'null') || {}
)
const messages = ref<
  Array<{
    subtitle: string | undefined
    text: string
    type: 'question' | 'answer'
  }>
>(
  JSON.parse(localStorage.getItem('onboarding.messages') || 'null') || [
    {
      subtitle: 'Hello, welcome to StoryFlame!',
      text: 'What is your name?',
      type: 'question',
    },
  ]
)
const lastQuestion = computed(() => {
  return messages.value
    .filter(
      (message) => message.type === 'question' || message.type === 'finish'
    )
    .at(-1)
})
const loading = ref(false)

const router = useRouter()

function checkIfFinished() {
  if (messages.value.filter((message) => message.type === 'finish').length) {
    router.push('/summary')
  }
}

function extractData(answer: string) {
  loading.value = true
  messages.value.push({
    subtitle: undefined,
    text: answer,
    type: 'answer',
  })

  testInput.value = ''

  axios
    .post<SuccessResponse<any>>('/api/v1/onboarding', {
      messages: messages.value,
      data: data.value,
    })
    .then((response) => {
      data.value = response.data.data.user
      messages.value.push(response.data.data.message)
      localStorage.setItem('onboarding.user', JSON.stringify(data.value))
      localStorage.setItem(
        'onboarding.messages',
        JSON.stringify(messages.value)
      )

      checkIfFinished()
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  checkIfFinished()
  axios.get<SuccessResponse<any>>('/api/v1/onboarding').then((response) => {
    questionsCount.value = response.data.data.questionsCount
  })
})
</script>
