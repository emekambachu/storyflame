<template>
  <div class="w-full max-w-80 flex items-center justify-between gap-2">
    <button
      v-if="isRecording"
      class="w-12 h-12 shrink-0 bg-black rounded-full flex items-center justify-center gap-1"
      @click="toggleRecording"
    >
      <div class="w-2 h-4 bg-pure-white rounded-sm" />
      <div class="w-2 h-4 bg-pure-white rounded-sm" />
    </button>

    <div class="relative w-24 h-24 mx-auto">
      <button
        class="absolute inset-0 bg-red-500 rounded-full z-20 flex items-center justify-center"
        @click="toggleRecording"
      >
        <span
          v-if="isRecording"
          class="text-pure-white text-lg font-bold"
        >
          {{ formattedTime }}
        </span>
        <span v-else>
          <microphone-icon class="text-white w-10 h-10" />
        </span>
      </button>
      <div
        v-if="isRecording"
        :style="{
          transform: `scale(${(140 + pulse) / 100})`,
        }"
        class="z-10 bg-red-500 opacity-75 absolute inset-0 rounded-full duration-75"
      />
      <div
        v-if="isRecording"
        :style="{
          transform: `scale(${(120 + pulse / 2) / 100})`,
        }"
        class="z-0 bg-red-500 opacity-50 absolute inset-0 rounded-full duration-75"
      />
    </div>

    <button
      v-if="isRecording"
      class="w-12 h-12 shrink-0 bg-black rounded-full flex items-center justify-center gap-1"
      @click="toggleRecording()"
    >
      <div class="w-4 h-4 bg-pure-white rounded-sm" />
    </button>
  </div>
</template>

<script lang="ts" setup>
import axios from 'axios'
import { ref, watch, computed } from 'vue'
import useVoice from '@/composables/useVoice'
import MicrophoneIcon from '@/components/icons/MicrophoneIcon.vue'

const emit = defineEmits(['transcribed'])

const { isRecording, listen, record, stop } = useVoice()

const audioLevel = ref<number>(0)
const pulse = computed(() => {
  return isRecording.value ? audioLevel.value : 10
})

const transcribedText = ref<string>('')

async function toggleRecording() {
  if (isRecording.value) {
    const { blob } = await stop()
    const formData = new FormData()
    formData.append('audio', blob)
    axios
      .post('/api/v1/transcribe', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((res) => {
        transcribedText.value = res.data.map((s: any) => s.text).join(' ')
        emit('transcribed', transcribedText.value)
      })
  } else {
    listen().then((stream) => {
      if (!stream.value) {
        return
      }
      record(stream.value)
      const context = new AudioContext()
      const analyzer = context.createAnalyser()
      const source = context.createMediaStreamSource(stream.value)
      source.connect(analyzer)

      const dataArray = new Uint8Array(analyzer.frequencyBinCount)

      const recorder = new MediaRecorder(stream.value)
      recorder.start()

      const draw = () => {
        if (!isRecording.value) {
          return
        }
        analyzer.getByteFrequencyData(dataArray)

        audioLevel.value =
          dataArray.reduce((acc, val) => acc + val, 0) / dataArray.length

        requestAnimationFrame(draw)
      }
      draw()
    })
  }
}

const recordingTime = ref<number>(300)
const formattedTime = computed(() => {
  const minutes = Math.floor(recordingTime.value / 60)
  const seconds = recordingTime.value % 60
  return `${minutes}:${seconds.toString().padStart(2, '0')}`
})

watch(isRecording, (newVal) => {
  if (newVal && isRecording) {
    const interval = setInterval(() => {
      if (recordingTime.value > 0) {
        recordingTime.value--
      } else {
        clearInterval(interval)
        toggleRecording()
      }
    }, 1000)
    return () => clearInterval(interval)
  }
})
</script>

<style scoped></style>
