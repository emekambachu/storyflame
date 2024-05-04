<template>
	<div
		:class="[
			selected ? 'border-red-300 bg-red-200' : 'border-gray-200 bg-white',
			textClass,
		]"
		class="p-6 rounded-full flex items-center gap-2 border-2 hover:border-red-300 border-gray-200 hover:bg-red-300 transition-colors duration-75 select-none"
	>
		<slot name="text" />
	</div>
</template>

<script lang="ts" setup>
const props = defineProps({
	textClass: {
		type: String,
		default: 'font-semibold text-xl text-neutral-700 hover:text-red-600',
	},
	selected: {
		type: Boolean,
		default: false,
	},
})
</script>

<style scoped></style>
