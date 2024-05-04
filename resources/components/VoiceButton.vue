<template>
	<div
		class="w-full max-w-80 grid grid-cols-[48px,auto,48px] grid-rows-1 gap-16 items-center"
	>
		<transition
			enter-active-class="transition-opacity duration-200"
			enter-from-class="opacity-0"
			enter-to-class="opacity-100"
			leave-active-class="transition-opacity duration-200"
			leave-from-class="opacity-100"
			leave-to-class="opacity-0"
		>
			<button
				v-if="isRecording"
				key="restart-button"
				class="grid grid-rows-[1fr,auto,1fr] group gap-1"
				@click="stop"
			>
				<span
					class="row-start-2 w-12 h-12 shrink-0 bg-black rounded-full flex items-center justify-center gap-1 select-none group-active:scale-95 transition-transform duration-75"
				>
					<restart-icon class="w-6 h-6 text-white" />
				</span>
				<span class="row-start-3 text-black text-xs font-bold">Restart</span>
			</button>
		</transition>

		<div
			key="start-stop-button"
			class="relative w-24 h-24 active:scale-95 transition-transform col-start-2 col-end-3"
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
					<app-loader
						v-if="isInitalizing"
						class="light w-8"
					/>
					<microphone-icon
						v-else
						class="text-white w-10 h-10"
					/>
				</span>
			</button>
			<div
				class="z-10 origin-[50%_51%] spin-inverse opacity-50 absolute inset-0"
			>
				<div
					:style="{
						transform: `scale(${isRecording && !isPaused ? (140 + pulse) / 100 : 1})`,
					}"
					class="duration-75 bg-red-500 h-full rounded-full"
				/>
			</div>
			<div class="z-10 origin-[51%_50%] spin opacity-60 absolute inset-0">
				<div
					:style="{
						transform: `scale(${isRecording && !isPaused ? (120 + pulse / 2) / 100 : 0})`,
					}"
					class="duration-75 bg-red-500 h-full rounded-full"
				/>
			</div>
		</div>

		<transition
			enter-active-class="transition-opacity duration-200"
			enter-from-class="opacity-0"
			enter-to-class="opacity-100"
			leave-active-class="transition-opacity duration-200"
			leave-from-class="opacity-100"
			leave-to-class="opacity-0"
		>
			<pause-play-button
				v-if="isRecording"
				:is-playing="!isPaused"
				class="shrink-0"
				@pause="pause"
				@play="resume"
			/>
		</transition>
	</div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import MicrophoneIcon from '@/components/icons/MicrophoneIcon.vue'
import useVoice from '@/composables/useVoice'
import axios from 'axios'
import PausePlayButton from '@/components/PausePlayButton.vue'
import RestartIcon from '@/components/icons/RestartIcon.vue'
import AppLoader from '@/components/AppLoader.vue'
import { SuccessResponse } from '@/types/responses'

const emit = defineEmits(['transcribed'])

const props = defineProps({
	limit: {
		type: Number,
		default: 5 * 60 * 1000, // 5 minutes
	},
})

const {
	isInitalizing,
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
				.post<SuccessResponse<any>>('/api/v1/transcribe', formData, {
					headers: {
						'Content-Type': 'multipart/form-data',
					},
				})
				.then((response) => {
					transcribedText.value = response.data.data.transcription
					console.log(transcribedText.value)
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

<style scoped>
@keyframes spin {
	100% {
		transform: rotate(1turn);
	}
}

.spin {
	animation: spin 5s ease-in infinite;
}

.spin-inverse {
	animation: spin 5s ease-out infinite reverse;
	animation-delay: 1s;
}
</style>
