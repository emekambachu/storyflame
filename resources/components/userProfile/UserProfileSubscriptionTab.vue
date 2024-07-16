<template>
    <div v-if="!isLoading" class="flex flex-col gap-10">
        <!-- Available Reports -->
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col gap-1 p-4 text-stone-800 border border-solid border-stone-300 rounded-lg">
                <div class="w-full flex flex-row justify-between text-stone-900">
                    <div class="font-semibold">
                        Report Credits Available
                    </div>
                    <div class="font-bold">
                        {{ subscriptionData?.available_report_count }}
                    </div>
                </div>
                <div class="w-full text-sm">
                    Used to create various report types for your stories and characters.
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-4">
            <h2 class="font-bold text-lg">Current Subscription</h2>
            <SubscriptionCard
                v-if="currentSubscription"
                :plan="currentSubscription"
                :is-current-subscription="true"
                :current-price-id="subscriptionData?.price_id"
                :next-billed-at="subscriptionData?.next_billed_at"
                :ends-at="subscriptionData?.ends_at"
                :transition-to-product-price="getTransitionToProductPrice"
                card-type="current"
                @switch-interval="handleIntervalSwitch"
                @cancel-transition="handleCancelSubscriptionChange"
            />
        </div>
        <div v-if="!isFreePlan && (showChangeMembership === false)">
            <button
                class="block w-full py-3 rounded-lg text-stone-700 hover:text-stone-50 hover:bg-stone-900 border border-stone-700 justify-center items-center gap-2.5 inline-flex text-base font-semibold"
                @click="showChangeMembership = true"
            >
                Change Membership Plan
            </button>
        </div>
        <div
            v-if="isFreePlan || showChangeMembership"
            class="flex flex-col gap-4"
        >
            <h2 class="font-bold text-lg">Available Plans</h2>
            <interval-switcher
                v-model="selectedInterval"
                :initial-interval="isFreePlan ? 'month' : 'year'"
            />

            <SubscriptionCard
                v-for="plan in availablePlans"
                :key="plan.id"
                :plan="plan"
                :is-current-subscription="false"
                :current-price-id="null"
                card-type="available"
                :selected-interval="selectedInterval"
                @select-plan="handlePlanSelection"
            />
        </div>

        <div v-if="!isFreePlan && !subscriptionData?.ends_at" class="w-full text-center">
            <button
                class="text-stone-600 hover:text-stone-900"
                @click="cancelCurrentSubscription"
            >
                Cancel Membership
            </button>
        </div>

        <div
            v-if="invoices.length > 0"
            class="w-full flex flex-col gap-4"
        >
            <h2 class="font-bold text-lg">Invoices</h2>
            <div class="w-full">
                <div v-for="invoice in invoices" :key="invoice.id"
                     class="w-full flex flex-row justify-between p-2 border-b border-stone-200">
                    <div>
                        {{ formatDate(invoice.billed_at) }} <span class="text-stone-500">for ${{ invoice.total / 100 }} ({{ invoice.currency }})</span>
                    </div>
                    <div
                        @click="loadInvoiceLinkInNewTab(invoice.paddle_id)"
                        class="cursor-pointer text-stone-500 hover:text-stone-900"
                    >View
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <p>Loading...</p>
    </div>
    <div v-if="error">
        <p class="error">{{ error }}</p>
    </div>
    <!-- Confirmation Modal -->
    <div v-if="showConfirmationModal && currentSubscription?.name"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="flex flex-col gap-8 bg-white p-6 md:p-8 rounded-lg max-w-md md:max-w-lg w-full">
            <div class="flex flex-row justify-between w-full items-center">
                <h2 class="text-stone-800 text-xl select-none">Confirm Subscription Change</h2>
                <button @click="closeSubscriptionChangeModel" class="p-1 text-stone-500 hover:text-stone-900">
                    <xmark-icon/>
                </button>
            </div>
            <div class="flex flex-col gap-2 w-full select-none">
                <div class="w-full flex flex-col rounded bg-stone-100 p-4">
                    <div class="flex flex-row justify-between items-center text-xs">
                        <div class="uppercase text-xs font-semibold text-stone-500">Current Plan</div>
                        <div class="text-right font-bold">
                            ${{ currentSubscriptionPrice / 100 }} per {{ currentSubscriptionInterval }}
                        </div>
                    </div>
                    <h3 class="font-semibold">{{ currentSubscription?.name }}</h3>
                    <div class="text-sm">
                        {{ currentSubscription.description }}
                    </div>
                </div>

                <div class="w-full text-sm text-center text-stone-500">
                    Switching to
                </div>

                <div class="w-full flex flex-col rounded bg-blue-100 p-4">
                    <div class="flex flex-row justify-between items-center text-xs">
                        <div class="uppercase text-xs font-semibold text-blue-500">New Plan</div>
                        <div class="text-right font-bold">
                            ${{ newSubscriptionPrice / 100 }} per {{ newSubscriptionInterval }}
                        </div>
                    </div>
                    <h3 class="font-semibold">{{ newSubscriptionName }}</h3>
                    <div class="text-sm">
                        {{ newSubscriptionDescription }}
                    </div>
                </div>
            </div>


            <div class="w-full flex flex-col">
                <div class="flex flex-row justify-between items-center w-full text-right text-sm">
                    <div class="text-stone-500">
                        Change Effective on
                    </div>
                    <div class="font-bold text-stone-900">
                        {{ getEffectiveChangeDate() }}
                    </div>
                </div>
                <div class="w-full h-px mt-2 mb-4 bg-stone-300"/>
                <button @click="confirmSubscriptionChange"
                        class="w-full py-2 bg-blue-600 hover:bg-stone-900 text-white rounded">Confirm Change
                </button>

            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {computed, ref, onMounted, PropType} from 'vue';
import {
    getSubscriptions,
    changeSubscription,
    cancelSubscription,
    getInvoices,
    getInvoiceLink,
    createCustomer,
    cancelSubscriptionChange
} from '@/utils/endpoints';
import User from "@/types/user.js";
import CheckIcon from "@/components/icons/CheckIcon.vue";
import SubscriptionCard from "@/components/cards/SubscriptionCard.vue";
import IntervalSwitcher from "@/components/ui/IntervalSwitcher.vue";
import {formatDate} from "@/utils/formatDate";
import XmarkIcon from "@/components/icons/XmarkIcon.vue";

const props = {
    user: {
        type: Object as PropType<User>,
        required: true,
    },
};

const isLoading = ref(true);
const subscriptionData = ref<any>(null);
const plans = ref<any[]>([]);
const invoices = ref([]);
const selectedInterval = ref('year');
const error = ref<string | null>(null);
const showChangeMembership = ref(false);
const showConfirmationModal = ref(false);
const currentSubscriptionPrice = ref(0);
const currentSubscriptionInterval = ref('');
const newSubscriptionName = ref('');
const newSubscriptionDescription = ref('');
const newSubscriptionPrice = ref(0);
const newSubscriptionInterval = ref('');
const pendingProductPricePaddleId = ref(null);

const fetchSubscriptions = async () => {
    try {
        const response = await getSubscriptions();
        subscriptionData.value = response.subscription;
        plans.value = response.plans;
        isLoading.value = false;
    } catch (err) {
        error.value = 'Failed to fetch subscriptions';
        isLoading.value = false;
    }
};

const currentSubscription = computed(() => {
    if (!subscriptionData.value) {
        return plans.value.find(plan => plan.paddle_id === 'free');
    }
    ;
    return plans.value.find(plan => plan.paddle_id === subscriptionData.value.product_id);
});

const getTransitionToProductPrice = computed(() => {
    if (!subscriptionData.value || !subscriptionData.value.new_product_price_id) {
        console.log('yeah, this is null')
        return null;
    }

    const newProductPrice = plans.value
        .flatMap(plan => plan.prices)
        .find(price => price.paddle_id === subscriptionData.value.new_product_price_id);

    const newProductPlan = plans.value.find(plan => plan.prices.some(price => price.paddle_id === subscriptionData.value.new_product_price_id));

    if (!newProductPrice) {
        console.log('nope, couldnt find the newProducePrice');
        return null;
    }

    return {
        name: newProductPlan.name,
        interval: newProductPrice.interval,
        transitionAt: subscriptionData.value.downgrade_at
    };
});

const availablePlans = computed(() =>
    plans.value
        .filter(plan =>
            plan.paddle_id !== subscriptionData.value?.product_id &&
            plan.prices.some(p => p.price > 0)
        )
        .map(plan => ({
            ...plan,
            displayPrice: plan.prices.find(p => p.interval === selectedInterval.value)?.price || 0
        }))
);

const handlePlanSelection = async (productPricePaddleId: string) => {
    pendingProductPricePaddleId.value = productPricePaddleId;
    const selectedPlan = plans.value.find(plan =>
        plan.prices.some(price => price.paddle_id === productPricePaddleId)
    );
    const selectedPrice = selectedPlan.prices.find(price => price.paddle_id === productPricePaddleId);

    setCurrentPriceAndInterval();

    newSubscriptionName.value = selectedPlan.name;
    newSubscriptionDescription.value = selectedPlan.description;
    newSubscriptionPrice.value = selectedPrice.price;
    newSubscriptionInterval.value = selectedPrice.interval;

    showConfirmationModal.value = true;
};

const handleIntervalSwitch = async (productPricePaddleId: string) => {
    pendingProductPricePaddleId.value = productPricePaddleId;
    const newPrice = currentSubscription.value.prices.find(price => price.paddle_id === productPricePaddleId);
    setCurrentPriceAndInterval();
    newSubscriptionName.value = currentSubscription.value.name;
    newSubscriptionDescription.value = currentSubscription.value.description;
    newSubscriptionPrice.value = newPrice.price;
    newSubscriptionInterval.value = newPrice.interval;

    showConfirmationModal.value = true;
};

const setCurrentPriceAndInterval = () => {
    const currentPrice = currentSubscription.value.prices?.find(price => price.paddle_id === subscriptionData.value.price_id);
    if (!currentPrice) {
        currentSubscriptionPrice.value = 0;
        currentSubscriptionInterval.value = '';
        return;
    }
    currentSubscriptionPrice.value = currentPrice.price;
    currentSubscriptionInterval.value = currentPrice.interval;
};

const getEffectiveChangeDate = () => {
    const today = new Date();
    const nextBilledAt = subscriptionData?.value?.next_billed_at;

    return nextBilledAt ? formatDate(nextBilledAt) : formatDate(today);
};

const closeSubscriptionChangeModel = () => {
    showConfirmationModal.value = false;
    pendingProductPricePaddleId.value = null;
    newSubscription.value = null;
};

const confirmSubscriptionChange = async () => {
    if (isLoading.value) return;
    try {
        isLoading.value = true;
        if (subscriptionData.value?.subscription_id) {
            await changeSubscription(subscriptionData.value.subscription_id, pendingProductPricePaddleId.value);
        } else {
            await createNewSubscription(pendingProductPricePaddleId.value);
        }
        await fetchSubscriptions(); // Refresh the subscription data
        showConfirmationModal.value = true;
        showConfirmationModal.value = false;
        isLoading.value = false;
    } catch (error) {
        console.error('Failed to change subscription:', error);
    }
};

const handleCancelSubscriptionChange = async () => {
    if (isLoading.value) return;
    try {
        isLoading.value = true;
        if (subscriptionData.value.subscription_id) {
            await cancelSubscriptionChange(subscriptionData.value.subscription_id);
        }
        await fetchSubscriptions();
        isLoading.value = false;
    } catch (error) {
        console.error('Failed to cancel subscription change:', error);
    }
};

const toggleBilling = (cycle) => {
    billingCycle.value = cycle;
};

const isFreePlan = () => {
    return currentSubscription.value?.paddle_id === 'free';
};

const isUpgrade = (plan: any) => {
    return plan.name === "Pro Membership" && currentSubscription.value?.items[0].product.name === "Premium Membership";
};

const changePlan = async (plan: any) => {
    try {
        const price = plan.prices?.find((price: any) => price.interval === billingCycle.value);
        if (!price) {
            throw new Error('Selected price not found');
        }
        const response = await changeSubscription({product_price_paddle_id: price.paddle_id});
        currentSubscription.value = response.subscription;
        await fetchSubscriptions(); // Refresh the subscription data
    } catch (err) {
        error.value = err.response?.data?.error || 'An error occurred while changing the plan';
    }
};

const cancelCurrentSubscription = async () => {
    try {
        const response = await cancelSubscription(subscriptionData.value.subscription_id);
        currentSubscription.value = response.subscription;
        await fetchSubscriptions(); // Refresh the subscription data
    } catch (err) {
        error.value = err.response?.data?.error || 'An error occurred while cancelling the subscription';
    }
};


const fetchInvoices = async () => {
    const response = await getInvoices();
    console.log('the invoices are ', response);
    invoices.value = response.invoices;
};

const loadInvoiceLinkInNewTab = async (transactionId) => {
    const response = await getInvoiceLink(transactionId);
    console.log('the invoice link response is ', response);
    window.open(response.url, '_blank');
};

const createNewSubscription = async (productPricePaddleId) => {
    if (!props.user.paddle_id) {
        isLoading.value = true;
        try {
            const response = await createCustomer();
            props.user = response.user;
            isLoading.value = false;
        } catch (err) {
            error.value = err.response.data.error || 'An error occurred while creating the customer';
            isLoading.value = false;
            return;
        }
    }

    if (props.user.paddle_id && !subscriptionData.value?.subscription_id) {
        Paddle.Checkout.open({
            items: [
                {
                    price_id: productPricePaddleId,
                    quantity: 1,
                },
            ],
            customer: {
                id: props.user.paddle_id,
            }
        });

        window.addEventListener('paddleCheckoutClosed', function (event) {
            isLoading.value = true;
            fetchSubscriptions();
        });
        //
        // window.addEventListener('checkout.completed', function(data) {
        //     console.log('Checkout completed:', data);
        //     isLoading.value = true;
        //     fetchSubscriptions();
        // });
        //
        // window.addEventListener('checkout.closed', function(data) {
        //     console.log('Checkout closed:', data);
        //     isLoading.value = true;
        //     fetchSubscriptions();
        // });
        //
        // window.addEventListener('checkout:closed', function(data) {
        //     console.log('Checkout closed:', data);
        //     isLoading.value = true;
        //     fetchSubscriptions();
        // });

        // Paddle.eventCallback('checkout.completed', function(data) {
        //     console.log('Checkout completed:', data);
        //     isLoading.value = true;
        //     fetchSubscriptions();
        // });
        //
        // Paddle.eventCallback('checkout.closed', function(data) {
        //     console.log('Checkout closed:', data);
        //     isLoading.value = true;
        //     fetchSubscriptions();
        // });
    }
};

onMounted(() => {
    fetchSubscriptions();
    fetchInvoices();
});
</script>

<style scoped>
.subscription-card {
    /* Add your TailwindCSS styles here */
}

.subscription-header {
    display: flex;
    justify-content: space-between;
}

.subscription-features {
    list-style-type: none;
}

.subscription-toggle {
    display: flex;
    gap: 10px;
}

.invoice-item {
    display: flex;
    justify-content: space-between;
}
</style>
