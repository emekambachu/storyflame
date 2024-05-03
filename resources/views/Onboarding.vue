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
          :percent="10"
          class="w-full"
        />
        <p class="text-gray-700 text-sm font-normal">1/2</p>
      </div>
    </aside>
    <main
      v-if="steps[step]"
      class="grow flex flex-col items-center h-full max-h-[432px] w-full"
    >
      <h3 class="text-neutral-950 opacity-60 text-sm font-normal mb-2">
        {{ steps[step].subtitle }}
      </h3>
      <h1 class="text-neutral-700 text-3xl font-bold">
        {{ steps[step].title }}
      </h1>

      <div
        v-if="isSpeakingMode"
        class="w-full mb-8 mt-auto flex flex-col gap-4"
      >
        <tooltip-message
          v-if="showTooltip"
          class="w-fit mx-auto text-pure-white"
        >
          <template #text><p class="text-red-600">Press to start</p></template>
        </tooltip-message>

        <tooltip-message
          v-if="!showTooltip"
          class="text-red-200 w-fit mx-auto mb-6"
          message-class="bg-red-200 px-3 py-2 text-center w-full rounded-lg text-red-600 text-xs font-normal min-h-9"
        >
          <template #text>
            <p class="text-red-600 flex items-center gap-2">
              <img src="../assets//images/time.png" />
              Hurry up! Only 30sec left!
            </p>
          </template>
        </tooltip-message>
        <voice-button
          class="mx-auto"
          @click="showTooltip = !showTooltip"
          @transcribed="
            (text) => {
              data[steps[step].property] = text
              step++
            }
          "
        />
      </div>
      <template v-else>
        <div
          v-if="true"
          class="w-full my-6 flex flex-col items-start gap-4"
        >
          <ListOption class="w-full">
            <template #text>Develop a story</template>
          </ListOption>

          <ListOption class="w-full">
            <template #text>Get feedback on a script</template>
          </ListOption>

          <ListOption class="w-full">
            <template #text>Other</template>
          </ListOption>
        </div>
        <template v-else>
          <textarea
            v-model="testInput"
            class="mx-auto my-auto w-full text-center text-2xl text-black font-normal bg-white !border-0 !outline-0"
            placeholder="Enter your answer"
          />
        </template>
      </template>
    </main>
    <!-- <pre class="text-start">{{ data }}</pre> -->
    <button
      class="text-neutral-500 text-base font-normal mb-0 mt-auto"
      @click="isSpeakingMode = !isSpeakingMode"
    >
      Switch to
      <span class="text-red-600 font-bold">
        {{ isSpeakingMode ? 'typing' : 'speaking' }}
      </span>
      mode
    </button>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import ListOption from '@/components/ListOption.vue'
import VoiceButton from '@/components/VoiceButton.vue'
import ProgressBar from '@/components/ProgressBar.vue'
import RepeatAnswer from '@/components/RepeatAnswer.vue'
import TooltipMessage from '@/components/TooltipMessage.vue'

const showRepeatAnswerModal = ref(false)
const testInput = ref('')
const isSpeakingMode = ref(true)
const step = ref(0)
const data = ref({
  name: '',
  goal: '',
  genre: '',
})
const showTooltip = ref(true)
const steps = computed(() => [
  {
    subtitle: 'Hello, welcome to StoryFlame!',
    title: 'What is your name?',
    property: 'name',
  },
  {
    subtitle: `Nice to meet you, ${data.value.name}!`,
    title: 'What is your goal?',
    property: 'goal',
  },
  {
    subtitle: 'Great!',
    title: 'What genre do you like?',
    property: 'genre',
  },
])
</script>
