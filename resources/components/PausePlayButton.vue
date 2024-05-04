<template>
	<button
		key="pause-button"
		class="group grid grid-rows-[1fr,auto,1fr] gap-1 items-center"
		@click="() => emit(isPlaying ? 'pause' : 'play')"
	>
		<span
			class="row-start-2 w-12 h-12 flex items-center justify-center gap-1 bg-black rounded-full select-none group-active:scale-95 transition-transform duration-75"
		>
			<template v-if="isPlaying">
				<span class="w-2 h-4 bg-pure-white rounded-sm" />
				<span class="w-2 h-4 bg-pure-white rounded-sm" />
			</template>
			<template v-else>
				<play-icon class="text-white h-4 w-4" />
			</template>
		</span>
		<span class="row-start-3 text-black text-xs font-bold">
			{{ isPlaying ? 'Pause' : 'Play' }}
		</span>
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
