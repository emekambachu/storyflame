<template>
    <component :is="is" :class="{
        'caret': interval,
    }">
        {{ _modelValue }}
    </component>
</template>

<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue'

const emit = defineEmits(['transitionend'])

const props = defineProps({
    is: {
        type: String,
        required: true,
    },
    modelValue: {
        type: String,
        default: () => undefined,
    },
    speed: {
        type: Number,
        default: 20,
    },
})

const _modelValue = ref('')
const interval = ref<number | null>(null)

watch(
    () => props.modelValue,
    (value) => {
        _modelValue.value = ''
        if (interval.value) {
            clearInterval(interval.value)
            interval.value = null
        }
        animateText()
    }
)

function animateText() {
    const chars = props.modelValue.split('')
    let i = 0
    interval.value = setInterval(() => {
        if (i < chars.length) {
            _modelValue.value += chars[i]
            i++
        } else {
            if (interval.value) {
                console.log('clearing interval')
                clearInterval(interval.value)
                interval.value = null
                emit('transitionend')
            }
        }
    }, props.speed)
}

onMounted(()=>{
    if (props.modelValue) {
        animateText()
    }
})
</script>

<style scoped>
.caret::after {
    content: '';
    width: 4px;
    height: 0.9em;
    margin-left: 1px;
    margin-bottom: -2px;
    background: black;

    display: inline-block;
    animation: caret 1s steps(1) infinite;
}

@keyframes caret {
    0%, 100% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
}
</style>
