<template>
    <div class="w-full bg-stone-100 rounded-lg flex">
        <button
            @click="selectInterval('month')"
            :class="[
                'grow shrink basis-0 px-3 py-2.5 rounded-lg flex justify-center items-center',
                selectedInterval === 'month' ? 'bg-stone-800' : ''
                ]"
        >
            <span :class="[
                'text-center font-medium',
                selectedInterval === 'month' ? 'text-stone-50' : 'text-stone-500'
                ]">Monthly</span>
        </button>
        <button
            @click="selectInterval('year')"
            :class="[
                'grow shrink basis-0 px-3 py-2.5 rounded-lg flex justify-center items-center gap-1',
                selectedInterval === 'year' ? 'bg-stone-800' : ''
                ]"
        >
            <span :class="[
                'text-center font-medium',
                selectedInterval === 'year' ? 'text-stone-50' : 'text-stone-500'
                ]"
            >
                Annual
            </span>
            <span class="text-center text-stone-400 text-sm font-medium">save 20%</span>
        </button>
    </div>
</template>

<script lang="ts" setup>
import {onMounted, ref, defineProps} from 'vue';
import {PropType} from "vue/dist/vue";
import User from "@/types/user";

const props = defineProps<{
    initialInterval: 'month' | 'year';
}>();

const selectedInterval = ref<'month' | 'year'>(props.initialInterval);

const emit = defineEmits<{
    (e: 'update:modelValue', value: 'month' | 'year'): void
}>();

const selectInterval = (interval: 'month' | 'year') => {
    selectedInterval.value = interval;
    emit('update:modelValue', interval);
};

onMounted(() => {
    selectInterval(props.initialInterval ?? 'year');
});
</script>
