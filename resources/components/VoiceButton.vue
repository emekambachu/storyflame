<template>
	<div class="w-full max-w-80 flex justify-center items-center gap-16">
		<transition
			enter-active-class="transition-opacity duration-300"
			enter-from-class="opacity-0"
			enter-to-class="opacity-100"
			leave-active-class="transition-opacity duration-300"
			leave-from-class="opacity-100"
			leave-to-class="opacity-0"
		>
			<pause-play-button
				v-if="isRecording"
				:is-playing="!isPaused"
				class="shrink-0 bg-black rounded-full"
				@pause="pause"
				@play="resume"
			/>
		</transition>

		<div
			key="start-stop-button"
			class="relative w-24 h-24"
		>
			<button
				class="absolute inset-0 bg-red-500 rounded-full z-20 flex items-center justify-center select-none"
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
				:style="{
					transform: `scale(${(isRecording && !isPaused) ? (140 + pulse) / 100 : 1})`,
				}"
				class="z-10 bg-red-500 opacity-75 absolute inset-0 rounded-full duration-75"
			/>
			<div
				:style="{
					transform: `scale(${(isRecording && !isPaused) ? (120 + pulse / 2) / 100 : 1})`,
				}"
				class="z-0 bg-red-500 opacity-50 absolute inset-0 rounded-full duration-75"
			/>
		</div>

		<transition
			enter-active-class="transition-opacity duration-300"
			enter-from-class="opacity-0"
			enter-to-class="opacity-100"
			leave-active-class="transition-opacity duration-300"
			leave-from-class="opacity-100"
			leave-to-class="opacity-0"
		>
			<button
				v-if="isRecording"
				key="stop-button"
				class="w-12 h-12 shrink-0 bg-black rounded-full flex items-center justify-center gap-1"
				@click="toggleRecording"
			>
				<div class="w-4 h-4 bg-pure-white rounded-sm" />
			</button>
		</transition>
	</div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import MicrophoneIcon from '@/components/icons/MicrophoneIcon.vue'
import useVoice from '@/composables/useVoice'
import axios from 'axios'
import PausePlayButton from '@/components/PausePlayButton.vue'

const emit = defineEmits(['transcribed'])

const props = defineProps({
	limit: {
		type: Number,
		default: 5 * 60 * 1000, // 5 minutes
	},
})

const {
	isRecording,
	isPaused,
	start,
	stop,
	pause,
	resume,
	onSilentError,
	duration,
} = useVoice()

onSilentError(() => {
	console.log('Silent error')
})

const audioLevel = ref<number>(0)
const pulse = computed(() => {
	return isRecording.value ? audioLevel.value : 10
})

const transcribedText = ref<string>('')
const loading = ref<boolean>(false)

async function toggleRecording() {
	try {
		if (isRecording.value) {
			const blob = stop()
			const formData = new FormData()
			formData.append('audio', blob)
			loading.value = true
			axios
				.post('/api/v1/transcribe', formData, {
					headers: {
						'Content-Type': 'multipart/form-data',
					},
				})
				.then((response) => {
					transcribedText.value = response.data.text
					emit('transcribed', transcribedText.value)
				})
		} else {
			void start((level: number) => {
				audioLevel.value = level
			})
		}
	} catch (e) {
		console.error(e)
	}
}

const remainingTime = computed(() => {
	return props.limit - duration.value
})

const showRemainingWarning = computed(() => {
	return remainingTime.value < 30 * 1000 // 30 seconds
})

const formattedTime = computed(() => {
	const seconds = Math.floor((remainingTime.value / 1000) % 60)
	const minutes = Math.floor(remainingTime.value / 1000 / 60)
	return `${minutes}:${seconds.toString().padStart(2, '0')}`
})
</script>

<style scoped></style>
