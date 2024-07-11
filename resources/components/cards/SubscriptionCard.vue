<template>
    <div class="w-full overflow-hidden bg-stone-50 rounded-xl border border-stone-200 flex-col justify-start items-start inline-flex">
        <div class="self-stretch px-4 py-2 bg-stone-200 justify-start items-center gap-2.5 inline-flex">
            <div class="grow shrink basis-0 text-stone-700 text-base font-semibold leading-snug">
                {{ plan.name }}
            </div>
            <div v-if="selectionStatus" class="justify-start items-center gap-1 flex">
                <div class="text-center text-stone-500 text-xs font-normal leading-none">
                    {{ selectionStatus }}
                </div>
                <div class="p-0.5 bg-stone-800 rounded-full justify-center items-start gap-2.5 flex">
                    <div class="w-3 h-3 relative text-white"><check-icon></check-icon></div>
                </div>
            </div>
        </div>
        <div class="self-stretch p-4 justify-end items-start gap-6 inline-flex">
            <div class="grow shrink basis-0 flex-col justify-center items-start gap-3 inline-flex text-sm">
                <div class="self-stretch flex-col justify-center items-start gap-1 flex">
                    <div v-for="(benefit, index) in plan.benefits?.includes" :key="index" class="self-stretch justify-start items-center gap-2 inline-flex leading-normal">
                        <div class="p-0.5 bg-zinc-100 rounded-[100px] justify-center items-start gap-2.5 flex">
                            <div class="w-3 h-3 relative text-stone-800"><check-icon></check-icon></div>
                        </div>
                        <div class="grow shrink basis-0 text-stone-700 font-medium">{{ benefit }}</div>
                    </div>
                </div>
            </div>
            <div class="self-stretch flex-col gap-3 justify-center items-end inline-flex">
                <div class="w-full">
                    <div class="text-center text-stone-900 text-2xl font-semibold leading-tight">
                        ${{ displayPrice }}
                    </div>
                    <div class="text-center text-stone-500 text-2xs font-bold leading-tight">
                        per {{ displayInterval }}
                    </div>
                </div>
                <button
                    v-if="cardType === 'available'"
                    @click="selectPlan"
                    class="bg-blue-500 hover:bg-stone-900 text-white text-xs p-2 rounded font-semibold capitalize"
                >
                    Select {{ displayInterval }}ly Plan
                </button>

                <div class="flex flex-col w-full text-center gap-1">
                    <button
                        v-if="cardType === 'current' && hasAlternateInterval"
                        @click="switchInterval"
                        class="bg-blue-500 hover:bg-stone-900 text-white text-xs p-2 rounded font-semibold capitalize"
                    >
                        Switch to {{ alternateInterval }}ly Plan
                    </button>
                    <div v-if="displayInterval === 'month'" class="text-xs text-stone-600">
                        Save ${{ ((displayPrice * 12) - alternatePrice).toFixed(0) }} a year <br />with annual billing
                    </div>
                    <div v-if="displayInterval === 'year'" class="text-xs text-stone-600">
                        Costs  an additional <br />${{ ((alternatePrice - (displayPrice /12))*12).toFixed(0) }} per year
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full p-4 text-sm">
            <div v-if="endsAt" class="w-full flex flex-row justify-between rounded bg-stone-200 text-stone-600 p-2">
                <div>
                    Membership ends on
                </div>
                <div>
                    // format date as Aug 9, 2024
                    {{ formatDate(endsAt) }}
                </div>
            </div>
            <div v-if="nextBilledAt" class="w-full flex flex-row justify-between rounded bg-stone-200 text-stone-600 p-2">
                <div>
                    Next billed on
                </div>
                <div>
                    {{ formatDate(nextBilledAt) }}
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import CheckIcon from "@/components/icons/CheckIcon.vue";
import {formatDate} from "@/utils/formatDate";

interface Price {
    id: string;
    paddle_id: string;
    interval: string;
    price: number;
}

interface Plan {
    id: string;
    paddle_id: string;
    name: string;
    benefits: {
        includes: string[];
    };
    prices: Price[];
}

const props = defineProps({
    plan: {
        type: Object,
        required: true,
    },
    subscriptionId: {
        type: String,
        required: false,
        default: null,
    },
    endsAt: {
        type: String,
        required: false,
        default: null,
    },
    isCurrentSubscription: {
        type: Boolean,
        required: true,
    },
    currentPriceId: {
        type: String,
        required: false,
        default: null,
    },
    selectedInterval: {
        type: String,
        required: true,
        validator: (value: string) => ['month', 'year'].includes(value),
    },
    nextBilledAt: {
        type: String,
        required: false,
    },
    cardType: {
        type: String,
        required: true,
        validator: (value: string) => ['current', 'available'].includes(value),
    },
});

const emit = defineEmits<{
    (e: 'selectPlan', priceId: string): void;
    (e: 'changeInterval', priceId: string): void;
}>();

const selectionStatus = computed(() => props.isCurrentSubscription ? 'Current' : '');

const currentPrice = computed(() => {
    if (props.isCurrentSubscription) {
        return props.plan.prices.find(price => price.paddle_id === props.currentPriceId) || props.plan.prices[0];
    } else {
        return props.plan.prices.find(price => price.interval === props.selectedInterval) || props.plan.prices[0];
    }
});

const displayPrice = computed(() => {
    if(!currentPrice.value) return (0).toFixed(0);
    return (currentPrice.value.price / 100).toFixed(0);
});

const displayInterval = computed(() => {
    if(!currentPrice.value) return '';
    return currentPrice.value.interval;
});

const hasAlternateInterval = computed(() => {
    return props.plan.prices.length > 1;
});

const alternateInterval = computed(() => {
    if(!props.isCurrentSubscription) return null;

    const otherPrice = props.plan.prices.find(price => price.paddle_id !== props.currentPriceId);
    return otherPrice ? otherPrice.interval : null;
});

const alternatePrice = computed(() => {
    if(!props.isCurrentSubscription) return null;

    const otherPrice = props.plan.prices.find(price => price.paddle_id !== props.currentPriceId);
    return otherPrice ? (otherPrice.price / 100).toFixed(0) : null;
});

const alternatePriceId = computed(() => {
    if(!props.isCurrentSubscription) return null;

    const otherPrice = props.plan.prices.find(price => price.paddle_id !== props.currentPriceId);
    return otherPrice ? otherPrice.paddle_id : null;
});

const selectPlan = () => {
    emit('selectPlan', currentPrice.value.paddle_id);
};

const switchInterval = () => {
    emit('switchInterval', alternatePriceId.value);
};
</script>
