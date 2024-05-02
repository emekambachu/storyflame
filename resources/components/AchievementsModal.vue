<template>
  <modal-bottom @close="emit('close')">
    <template #content>
      <div class="w-full flex flex-col items-center text-center">
        <img
          src="@/assets/images/emoji_trophy.png"
          class="w-12 h-12 shrink-0 mb-4"
          :class="{
            brightness: item.percent !== 100,
          }"
        />

        <h2 class="text-2xl text-black font-bold mb-2">{{ item.title }}</h2>
        <p class="text-base text-neutral-700 font-normal mb-10">
          {{ item.description }}
        </p>

        <div
          v-if="item.percent !== 100"
          class="w-full flex flex-col items-center gap-2 mb-10"
        >
          <div class="w-full flex items-center gap-6">
            <progress-bar
              :percent="item.percent"
              class="w-full"
            />
            <span class="text-gray-700 text-sm font-normal">
              {{ item.percent }}%
            </span>
          </div>

          <p
            v-if="item.hint"
            class="text-sm text-slate-400 font-normal"
          >
            {{ item.hint }}
          </p>
        </div>

        <button
          class="w-full flex items-center justify-center bg-red-600 select-none rounded-full p-4"
        >
          <span class="text-pure-white text-base text-semibold">
            {{ item.percent ? 'Got it, thanks!' : 'Continue writing my story' }}
          </span>
        </button>

        <button class="mt-3 text-base text-slate-400">Got it, thanks!</button>
      </div>
    </template>
  </modal-bottom>
</template>

<script setup lang="ts">
import ProgressBar from '@/components/ProgressBar.vue'
import ModalBottom from '@/components/ModalBottom.vue'

const emit = defineEmits(['close'])

//todo: replace with props or smth else
const item = {
  icon: { path: '' },
  title: 'Ice Breaker',
  description:
    'You’ve earned this achievement by going through the onboarding.',
  percent: 10,
  hint: 'Less than 7 hours left to finish!',
}
</script>

<style scoped lang="css">
.brightness {
  filter: brightness(0.8) contrast(0.3);
}
</style>
