<!-- /components/banners/FreeTrialBanner.vue -->
<template>
    <div v-if="showBanner" class="flex sticky justify-center w-full py-2 px-2 bg-blue-purple-gradient text-white">
        <div class="flex flex-row justify-center w-full max-w-screen-lg cursor-pointer select-none" @click="goToUpgrade">
            <div class="flex flex-col gap-1 items-center text-center">
                <div v-if="status === 'messages-available'" class="font-semibold text-sm md:text-base">
                    {{ dailyRemainingInteractions }} messages left today
                    <span class="font-normal block md:inline-block text-xs md:text-sm"> with {{ totalRemainingInteractions }} left in your free trial.</span>
                </div>
                <div v-else-if="status === 'daily-limit-reached'" class="font-semibold text-sm md:text-base">
                    Free trial limit reached for today,
                    <span class="font-normal block md:inline-block text-xs md:text-sm">come back tomorrow to develop further.</span>
                </div>
                <div v-else-if="status === 'trial-limit-reached'" class="font-semibold text-sm md:text-base">
                    You have run out of messages in your free trial.
                </div>
                <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                    <div class="text-2xs opacity-70 hidden md:block">
                        Upgrade to unlock unlimited messages + additional features!
                    </div>
                    <div class="text-2xs underline">Upgrade now</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUser } from '@/composables/query/user';
import { useFreeTrialStore } from '@/stores/freeTrial';

const props = defineProps({
    showBanner: {
        type: Boolean,
        required: true
    }
});

const router = useRouter();
const { data: user } = useUser();
const freeTrialStore = useFreeTrialStore();

const totalRemainingInteractions = computed(() => freeTrialStore.totalRemainingInteractions);
const dailyRemainingInteractions = computed(() => freeTrialStore.dailyRemainingInteractions);

const status = computed(() => {
    if (dailyRemainingInteractions.value > 0) {
        return 'messages-available';
    } else if (totalRemainingInteractions.value > 0) {
        return 'daily-limit-reached';
    } else {
        return 'trial-limit-reached';
    }
});

const goToUpgrade = () => {
    router.push({
        name: 'profile',
        query: { tab: 'membership' }
    });
}
</script>
