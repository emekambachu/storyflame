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
			<span class="w-4 h-4 bg-pure-white rounded-sm" />
		</template>
	</button>
</template>

<script lang="ts" setup>
const emit = defineEmits(['play', 'pause'])

defineProps({
	isPlaying: Boolean,
})
</script>

<style scoped></style>
