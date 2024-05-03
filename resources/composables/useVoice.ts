import {
	createEventHook,
	useDevicesList,
	usePermission,
	useUserMedia,
} from '@vueuse/core'
import { ref } from 'vue'
import { useLogger } from 'vue-logger-plugin'

const SILENCE_THRESHOLD = 1
const SILENCE_CHECK_DURATION = 5000

const audioTypes = ['webm', 'mp4', 'wav']
const codecs = [
	'vp9',
	'vp9.0',
	'vp8',
	'vp8.0',
	'avc1',
	'av1',
	'h265',
	'h.265',
	'h264',
	'h.264',
	'opus',
	'pcm',
	'aac',
	'mpeg',
	'mp4a',
]
const supportedMimeType = getSupportedMimeType()

function getSupportedMimeType() {
	for (const audioType of audioTypes) {
		for (const codec of codecs) {
			for (const variation of [
				`audio/${audioType};codecs=${codec}`,
				`audio/${audioType};codecs=${codec.toUpperCase()}`,
			]) {
				if (MediaRecorder.isTypeSupported(variation)) {
					return variation
				}
			}
		}
	}
	return undefined
}

export default function useVoice() {
	const logger = useLogger()
	const hasPermission = usePermission('microphone')
	const stopListening = ref<(() => void) | undefined>(undefined)
	const isRecording = ref(false)
	const isPaused = ref(false)
	const recorder = ref<MediaRecorder | undefined>(undefined)
	const chunks = ref<Blob[]>([])
	const duration = ref<number>(0)

	const onSilentError = createEventHook<boolean>()

	if (!supportedMimeType) {
		logger.error('No supported MIME type found')
		// log browser info
		logger.debug('Browser:', navigator.userAgent)
		throw new Error('No supported MIME type found')
	}

	const listen = async (): Promise<MediaStream> => {
		logger.debug('Starting to listen...')

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
				if (!microphones.value.length) {
					if (!microphones.value.find((i) => i.deviceId === currentMic.value)) {
						currentMic.value = microphones.value[0]?.deviceId
						logger.debug('Current microphone:', currentMic.value)
					}
				}
			},
		})

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

		stopListening.value = stop
		const stream = await start()
		if (!stream) {
			throw new Error('No stream found')
		}
		isRecording.value = true
		return stream
	}

	const record = (stream: MediaStream) => {
		logger.debug(`Starting to record using MIME type: ${supportedMimeType}`)

		// One more check for MIME type support 🤷, just in case
		if (!MediaRecorder.isTypeSupported(supportedMimeType)) {
			logger.error('MIME type is not supported:', supportedMimeType)
			stream.getTracks().forEach((track) => track.stop())
			isRecording.value = false
			return
		}

		// Create a new MediaRecorder instance
		recorder.value = new MediaRecorder(stream, {
			mimeType: supportedMimeType,
		})

		// Reset chunks
		chunks.value = []

		let durationInterval: number | undefined
		let offsetTimestamp: number = 0
		let now = Date.now()

		const startDurationInterval = () =>
			setInterval(() => {
				now = Date.now()
				duration.value += now - offsetTimestamp
				offsetTimestamp = now
			}, 250)

		recorder.value.onstart = (e) => {
			duration.value = 0
			offsetTimestamp = Date.now()
			durationInterval = startDurationInterval()

			isPaused.value = false
			logger.debug('Recording started')
		}

		recorder.value.onpause = (e) => {
			clearInterval(durationInterval)

			duration.value += Date.now() - offsetTimestamp
			isPaused.value = true
			logger.debug('Recording paused')
		}

		recorder.value.onresume = (e) => {
			offsetTimestamp = Date.now()
			durationInterval = startDurationInterval()
			isPaused.value = false
			logger.debug('Recording resumed')
		}

		// Save each chunk of data
		recorder.value.ondataavailable = (e) => {
			chunks.value.push(e.data)
		}

		// When recording stops, set the flag to false
		recorder.value.onstop = () => {
			clearInterval(durationInterval)
			duration.value += Date.now() - offsetTimestamp
			logger.debug('Recording stopped')
			isRecording.value = false
			// stop using the microphone
			stopListening.value?.()
		}

		// Start recording by chunks of 1 second
		recorder.value.start(1)
	}

	const connectWaveform = (
		stream: MediaStream,
		callback: (level: number) => void
	) => {
		const context = new AudioContext()
		const analyzer = context.createAnalyser()
		const source = context.createMediaStreamSource(stream)
		source.connect(analyzer)

		const dataArray = new Uint8Array(analyzer.frequencyBinCount)

		let isCheckingSilence = true
		let level = 0
		const draw = () => {
			if (!isRecording.value) {
				return
			}
			analyzer.getByteFrequencyData(dataArray)
			level = dataArray.reduce((acc, val) => acc + val, 0) / dataArray.length
			callback(level)

			if (isCheckingSilence) {
				if (level >= SILENCE_THRESHOLD) {
					logger.debug('Silence check passed')
					isCheckingSilence = false
				}
				if (duration.value > SILENCE_CHECK_DURATION) {
					logger.error('Silence check failed')
					isCheckingSilence = false
					void onSilentError.trigger(true)
					return
				}
			}

			requestAnimationFrame(draw)
		}
		draw()
		logger.debug('Waveform connected')
	}

	const stop = () => {
		recorder.value?.stop()
		return new Blob(chunks.value, {
			type: supportedMimeType,
		})
	}

	onSilentError.on((isSilent) => {
		console.log('Silent error:', isSilent)
		stop()
	})

	return {
		hasPermission,
		isRecording,
		isPaused,
		duration,
		onSilentError: onSilentError.on,
		listen,
		record,
		connectWaveform,
		start: async (waveformCallback: (level: number) => void) => {
			const stream = await listen()
			connectWaveform(stream, waveformCallback)
			record(stream)
		},
		stop,
		pause: () => {
			if (!isRecording.value || isPaused.value) {
				return
			}
			recorder.value?.pause()
		},
		resume: () => {
			if (!isRecording.value || !isPaused.value) {
				return
			}
			recorder.value?.resume()
		},
	}
}
