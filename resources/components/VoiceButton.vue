<template>
    <div class="relative w-20 h-20">
        <button class="absolute inset-0 bg-green-500 rounded-full z-20" @click="toggleRecording">
            {{ isRecording ? 'Stop' : 'Start' }}
        </button>
        <div :style="{
                transform: `scale(${(100 + pulse) / 100})`,
            }" class="z-10 bg-green-500 opacity-75 absolute inset-0 rounded-full duration-75" />
        <div :style="{
                transform: `scale(${(100 + pulse / 2) / 100})`,
            }" class="z-0 bg-green-500 opacity-50 absolute inset-0 rounded-full duration-75" />
    </div>
</template>

<script lang="ts" setup>

import useVoice from '../composables/useVoice'
import { computed, ref } from 'vue'
import axios from 'axios'

const emit = defineEmits([
    'transcribed',
])

const {
    isRecording,
    listen,
    record,
    stop,
} = useVoice()

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
        axios.post('/api/v1/transcribe', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        }).then((res) => {
            transcribedText.value = res.data.map((s: any) => s.text).join(' ')
            emit('transcribed', transcribedText.value)
        })
    } else {
        listen().then((stream) => {
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

                audioLevel.value = dataArray.reduce((acc, val) => acc + val, 0) / dataArray.length

                requestAnimationFrame(draw)
            }
            draw()
        })
    }
}
</script>

<style scoped>

</style>
