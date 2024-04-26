import { useDevicesList, usePermission, useUserMedia } from '@vueuse/core'
import { ref } from 'vue'

export default function useVoice() {
    const hasPermission = usePermission('microphone')
    const stopRecording = ref<(() => void) | undefined>(undefined)
    const isRecording = ref(false)
    const stream = ref<MediaStream | undefined>(undefined)
    const track = ref<MediaStreamTrack | undefined>(undefined)
    const recorder = ref<MediaRecorder | undefined>(undefined)
    const chunks = ref<Blob[]>([])
    const blob = ref<Blob | undefined>(undefined)
    const audioUrl = ref<string | undefined>(undefined)

    const listen = async () => {
        const constraints = {
            audio: {
                channelCount: 1,
                echoCancellation: true,
                sampleRate: 44100,
                sampleSize: 16,
            },
            video: false,
        }

        const currentMic = ref<string>()
        const { audioInputs: microphones } = useDevicesList({
            requestPermissions: true,
            constraints: constraints,
            onUpdated() {
                if (!microphones.value.find(i => i.deviceId === currentMic.value))
                    currentMic.value = microphones.value[0]?.deviceId
            },
        })
        console.log(microphones.value)

        const { start, stop } = useUserMedia({
            constraints: {
                audio: {
                    deviceId: currentMic.value,
                    channelCount: 1,
                    echoCancellation: true,
                    noiseSuppression: true,
                    sampleRate: 44100,
                    sampleSize: 16,
                },
            },
        })

        stopRecording.value = stop
        stream.value = await start()
        track.value = stream.value?.getAudioTracks()[0]
        isRecording.value = true
        return stream
    }

    const record = async (stream: MediaStream) => {
        console.log('recording')
        recorder.value = new MediaRecorder(stream)
        chunks.value = []
        blob.value = undefined
        audioUrl.value = undefined

        recorder.value.ondataavailable = (e) => {
            chunks.value.push(e.data)
        }

        recorder.value.start(0.1)
    }

    const readBlob = async (blob: Blob) => {
        const reader = new FileReader()
        reader.readAsArrayBuffer(blob)
        await new Promise((resolve) => {
            reader.onload = () => {
                resolve(true)
            }
        })
        return reader.result
    }

    const process = async (blob: Blob) => {
        const context = new AudioContext()
        const buffer = await readBlob(blob)
        console.log(buffer)

        const decodedData = await context.decodeAudioData(buffer as ArrayBuffer)
        const audioBuffer = decodedData.getChannelData(0) // Get audio data
        const threshold = 0.1 // Adjust this threshold as needed
        const filteredAudio = audioBuffer.filter(sample => Math.abs(sample) > threshold)

        console.log(filteredAudio)

        // Convert filtered audio back to Blob
        return new Blob([filteredAudio], { type: 'audio/wav' })
    }

    return {
        hasPermission,
        isRecording,
        stream,
        track,
        listen,
        record,
        process,
        stop: async () => {
            isRecording.value = false
            stopRecording.value?.()
            recorder.value?.stop()
            blob.value = new Blob(chunks.value, { type: 'audio/wav' })
            console.log(blob.value)
            audioUrl.value = URL.createObjectURL(blob.value)
            console.log(blob.value, audioUrl.value)
            return {
                blob: blob.value,
                audioUrl: audioUrl.value,
            }
        },
    }
}
