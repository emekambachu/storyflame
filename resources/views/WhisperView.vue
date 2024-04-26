<template>
    <div class="flex justify-center">
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
    </div>
    <audio ref="audio" controls type="audio/ogg" />
    <pre>{{transcribedText}}</pre>
</template>

<script lang="ts" setup>

import useVoice from '../composables/useVoice'
import { computed, ref } from 'vue'
import axios from 'axios'

const {
    stream,
    track,
    isRecording,
    listen,
    record,
    stop,
    process,
} = useVoice()

const audioLevel = ref<number>(0)
const pulse = computed(() => {
    return isRecording.value ? audioLevel.value : 10
})

const audio = ref<HTMLAudioElement | undefined>(undefined)
const transcribedText = ref<string>('')

async function toggleRecording() {
    if (isRecording.value) {
        const { audioUrl, blob } = await stop()
        audio.value!.src = audioUrl
        const formData = new FormData()
        formData.append('audio', blob)
        axios.post('/api/v1/transcribe', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        }).then((res) => {
            console.log(res.data)
            transcribedText.value = res.data.map((segment: any) => {
                return `[${segment.start}s - ${segment.end}s] ${segment.text}`
            }).join('\n')
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
