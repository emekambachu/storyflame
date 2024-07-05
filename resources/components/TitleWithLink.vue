<template>
    <div class="flex w-full items-center justify-between px-4">
        <slot>
            <h4>{{ title }}</h4>
        </slot>

        <button
            class="font-normal text-slate-400"
            @click="handleClick"
        >
            {{ buttonText }}
        </button>
    </div>
</template>
<script lang="ts" setup>
import { useRouter } from 'vue-router'

const emit = defineEmits(['see-all'])

const props = defineProps({
    title: {
        type: String,
        default: () => undefined,
    },
    buttonText: {
        type: String,
        default: 'See all',
    },
    to: {
        type: [String, Object],
        default: () => undefined,
    },
})

const router = useRouter()

function handleClick() {
    if (props.to) {
        router.push(props.to)
    } else {
        emit('see-all')
    }
}
</script>
