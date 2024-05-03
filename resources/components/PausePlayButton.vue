<template>
	<button
		key="pause-button"
		class="w-12 h-12 flex items-center justify-center gap-1"
		@click="() => emit(isPlaying ? 'pause' : 'play')"
	>
		<template v-if="isPlaying">
			<span class="w-2 h-4 bg-pure-white rounded-sm" />
			<span class="w-2 h-4 bg-pure-white rounded-sm" />
		</template>
		<template v-else>
			<play-icon class="text-white h-4 w-4"/>
		</template>
	</button>
</template>

<script lang="ts" setup>
import PlayIcon from '@/components/icons/PlayIcon.vue'

const emit = defineEmits(['play', 'pause'])

defineProps({
	isPlaying: Boolean,
})
</script>

<style scoped></style>
