<template>
    <template
        v-for="(text, index) in props.texts"
        :key="text.modelValue"
    >
        <animated-text
            :is="text.is"
            v-if="index <= visibleIndex"
            :class="text.class"
            :model-value="text.modelValue"
            @transitionend="visibleIndex++"
        />
    </template>
</template>

<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue'
import AnimatedText from '@/components/AnimatedText.vue'

const props = withDefaults(
    defineProps<{
        texts: {
            modelValue: string
            class: string
            is: string
        }[]
    }>(),
    {}
)

const visibleIndex = ref(0)

watch(
    () => props.texts,
    (value, oldValue) => {
        console.log('texts changed', value, oldValue)
        // detect what line is changed and move the visible index to that line
        const changedIndex = value.findIndex((text, index) => text.modelValue !== oldValue[index].modelValue)
        console.log('changedIndex', changedIndex)
        if (changedIndex !== -1) {
            visibleIndex.value = changedIndex
        } else {
            visibleIndex.value = -1
            requestAnimationFrame(() => {
                visibleIndex.value = 0
            })
        }
    }
)

onMounted(() => {
    requestAnimationFrame(() => {})
})
</script>

<style scoped></style>
