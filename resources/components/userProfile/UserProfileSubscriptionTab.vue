<!--
getSubscriptions() returns the following:

{
    "subscriptions": [
        {
            "id": 8,
            "billable_type": "App\\Models\\User",
            "billable_id": 38,
            "type": "default",
            "paddle_id": "sub_01j2cym5g2bgn8gwjbrk64arm2",
            "status": "active",
            "trial_ends_at": null,
            "paused_at": null,
            "ends_at": null,
            "next_billed_at": "2024-08-09 23:52:52",
            "created_at": "2024-07-09T23:52:55.000000Z",
            "updated_at": "2024-07-09T23:52:55.000000Z",
            "items": [
                {
                    "id": 7,
                    "subscription_id": 8,
                    "product_id": "pro_01j2796zwtbtyp4x1gphtjen2z",
                    "price_id": "pri_01j2798w4jks9xccmxcg95ktkf",
                    "status": "active",
                    "quantity": 1,
                    "created_at": "2024-07-09T23:52:55.000000Z",
                    "updated_at": "2024-07-09T23:52:55.000000Z",
                    "product": {
                        "id": 4,
                        "paddle_id": "pro_01j2796zwtbtyp4x1gphtjen2z",
                        "name": "Premium Membership",
                        "type": "subscription",
                        "description": "Everything you need to develop your stories.",
                        "benefits": {
                            "includes": [
                                "Unlimited Development",
                                " 4 Stories per month",
                                "1 Assessment Report \/ month"
                            ],
                            "excludes": []
                        },
                        "details": null,
                        "status": "active",
                        "order": 2,
                        "created_at": "2024-07-10T01:34:20.000000Z",
                        "updated_at": "2024-07-10T01:34:20.000000Z",
                        "deleted_at": null
                    },
                    "product_price": {
                        "id": 4,
                        "paddle_id": "pri_01j2798w4jks9xccmxcg95ktkf",
                        "product_id": 4,
                        "name": "Monthly subscription",
                        "description": "Monthly subscription",
                        "interval": "month",
                        "interval_frequency": 1,
                        "price": 2200,
                        "currency_code": "USD",
                        "status": "active",
                        "created_at": "2024-07-10T01:35:07.000000Z",
                        "updated_at": "2024-07-10T01:35:07.000000Z",
                        "deleted_at": null
                    }
                }
            ]
        }
    ],
    "plans": [
        {
            "id": 3,
            "paddle_id": "free",
            "name": "Free Trial",
            "type": "subscription",
            "description": "Try it out for free.",
            "benefits": {
                "includes": [
                    "Limited Development",
                    "1 Story"
                ],
                "excludes": [
                    "No Assessment Reports"
                ]
            },
            "created_at": "2024-07-10T01:30:40.000000Z",
            "updated_at": "2024-07-10T01:30:40.000000Z",
            "prices": []
        },
        {
            "id": 4,
            "paddle_id": "pro_01j2796zwtbtyp4x1gphtjen2z",
            "name": "Premium Membership",
            "type": "subscription",
            "description": "Everything you need to develop your stories.",
            "benefits": {
                "includes": [
                    "Unlimited Development",
                    " 4 Stories per month",
                    "1 Assessment Report \/ month"
                ],
                "excludes": []
            },
            "created_at": "2024-07-10T01:34:20.000000Z",
            "updated_at": "2024-07-10T01:34:20.000000Z",
            "prices": [
                {
                    "id": 4,
                    "paddle_id": "pri_01j2798w4jks9xccmxcg95ktkf",
                    "product_id": 4,
                    "name": "Monthly subscription",
                    "description": "Monthly subscription",
                    "price": 2200,
                    "currency_code": "USD",
                    "status": "active",
                    "created_at": "2024-07-10T01:35:07.000000Z",
                    "updated_at": "2024-07-10T01:35:07.000000Z"
                },
                {
                    "id": 5,
                    "paddle_id": "pri_01j279ekkjrrrwftj2td0s60pj",
                    "product_id": 4,
                    "name": "Annual Subscription",
                    "description": "Annual subscription",
                    "price": 21100,
                    "currency_code": "USD",
                    "status": "active",
                    "created_at": "2024-07-10T01:35:18.000000Z",
                    "updated_at": "2024-07-10T01:35:18.000000Z"
                }
            ]
        },
        {
            "id": 1,
            "paddle_id": "pro_01j279fckenjrqm5a7yee72xrx",
            "name": "Pro Membership",
            "type": "subscription",
            "description": "For professional writing volume",
            "benefits": {
                "includes": [
                    "Unlimited Development",
                    "Unlimited Stories",
                    "120 Assessment Reports"
                ],
                "excludes": []
            },
            "created_at": "2024-07-10T01:08:23.000000Z",
            "updated_at": "2024-07-10T01:08:23.000000Z",
            "prices": [
                {
                    "id": 1,
                    "paddle_id": "pri_01j279g5csyzyvj5f9dzw66tjz",
                    "product_id": 1,
                    "name": "Monthly subscription",
                    "description": "Monthly subscription.",
                    "price": 5500,
                    "currency_code": "USD",
                    "status": "active",
                    "created_at": "2024-07-10T01:23:31.000000Z",
                    "updated_at": "2024-07-10T01:23:31.000000Z"
                },
                {
                    "id": 2,
                    "paddle_id": "pri_01j279gtpyx9h8zretv3a3jd8s",
                    "product_id": 1,
                    "name": "Annual subscription",
                    "description": "Annual subscription",
                    "price": 52800,
                    "currency_code": "USD",
                    "status": "active",
                    "created_at": "2024-07-10T01:24:00.000000Z",
                    "updated_at": "2024-07-10T01:24:00.000000Z"
                }
            ]
        }
    ]
}
-->

<template>
<div v-if="!isLoading" class="flex flex-col gap-10">
<!-- Current Subscription -->
        <div class="flex flex-col gap-4">
            <h2 class="font-bold text-lg">Current Subscription</h2>
            <SubscriptionCard
                v-if="currentSubscription"
                :plan="currentSubscription"
                :is-current-subscription="true"
                :current-price-id="subscriptionData.price_id"
                :next-billed-at="subscriptionData.next_billed_at"
                card-type="current"
                @switch-interval="handleIntervalSwitch"
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

        <div v-if="!isFreePlan" class="w-full text-center">
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
                <div v-for="invoice in invoices" :key="invoice.id" class="w-full flex flex-row justify-between p-2 border-b border-stone-200">
                    <div>
                        {{ formatDate(invoice.billed_at) }} <span class="text-stone-500">for ${{ invoice.total / 100 }} ({{invoice.currency}})</span>
                    </div>
                    <div
                        @click="loadInvoiceLinkInNewTab(invoice.paddle_id)"
                        class="cursor-pointer text-stone-500 hover:text-stone-900"
                    >View</div>
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
    <div v-if="showConfirmationModal && currentSubscription?.name" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Confirm Subscription Change</h2>
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
            <div class="w-full text-sm font-semibold py-4 text-center text-stone-700">
                Switching to
            </div>

            <div class="w-full flex flex-col rounded bg-stone-100 p-4">
                <div class="flex flex-row justify-between items-center text-xs">
                    <div class="uppercase text-xs font-semibold text-stone-500">New Plan</div>
                    <div class="text-right font-bold">
                        ${{ newSubscriptionPrice / 100 }} per {{ newSubscriptionInterval }}
                    </div>
                </div>
                <h3 class="font-semibold">{{ newSubscriptionName }}</h3>
                <div class="text-sm">
                    {{ newSubscriptionDescription }}
                </div>
            </div>

            <div class="w-full h-px my-4 bg-stone-300"/>

            <div class="w-full flex flex-col gap-1.5">
                <div class="w-full text-right text-sm">
                    <strong>Change Effective:</strong> {{ formatDate(subscriptionData.next_billed_at) }}
                </div>

                <div class="flex justify-end gap-4">
                    <button @click="cancelSubscriptionChange" class="px-4 py-2 border border-solid border-stone-200 rounded text-stone-500 hover:bg-stone-200 hover:text-stone-900">Cancel</button>
                    <button @click="confirmSubscriptionChange" class="px-4 py-2 bg-blue-500 hover:bg-stone-900 text-white rounded">Confirm Change</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {computed, ref, onMounted, PropType} from 'vue';
import { getSubscriptions, changeSubscription, cancelSubscription, getInvoices, getInvoiceLink, createCustomer } from '@/utils/endpoints';
import User from "@/types/user.js";
import CheckIcon from "@/components/icons/CheckIcon.vue";
import SubscriptionCard from "@/components/cards/SubscriptionCard.vue";
import IntervalSwitcher from "@/components/ui/IntervalSwitcher.vue";
import {formatDate} from "@/utils/formatDate";

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
    if (!subscriptionData.value) return null;
    return plans.value.find(plan => plan.paddle_id === subscriptionData.value.product_id);
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

    newSubscriptionName.value = selectedPlan.name;
    newSubscriptionDescription.value = selectedPlan.description;
    newSubscriptionPrice.value = selectedPrice.price;
    newSubscriptionInterval.value = selectedPrice.interval;

    showConfirmationModal.value = true;
};

const handleIntervalSwitch = async (productPricePaddleId: string) => {
    pendingProductPricePaddleId.value = productPricePaddleId;
    const newPrice = currentSubscription.value.prices.find(price => price.paddle_id === productPricePaddleId);
    const currentPrice = currentSubscription.value.prices.find(price => price.paddle_id === subscriptionData.value.price_id);
    const currentName = currentSubscription.value.name;
    const currentDescription = currentSubscription.value.description;

    currentSubscriptionPrice.value = currentPrice.price;
    currentSubscriptionInterval.value = currentPrice.interval;
    newSubscriptionName.value = currentName;
    newSubscriptionDescription.value = currentDescription;
    newSubscriptionPrice.value = newPrice.price;
    newSubscriptionInterval.value = newPrice.interval;

    showConfirmationModal.value = true;
};

const cancelSubscriptionChange = () => {
    showConfirmationModal.value = false;
    pendingProductPricePaddleId.value = null;
    newSubscription.value = null;
};

const confirmSubscriptionChange = async () => {
    try {
        if (subscriptionData.value.subscription_id) {
            await changeSubscription(subscriptionData.value.subscription_id, pendingProductPricePaddleId.value);
        } else {
            await createNewSubscription(pendingProductPricePaddleId.value);
        }
        await fetchSubscriptions(); // Refresh the subscription data
        showConfirmationModal.value = false;
    } catch (error) {
        console.error('Failed to change subscription:', error);
    }
};

const toggleBilling = (cycle) => {
    billingCycle.value = cycle;
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
        const response = await changeSubscription({ product_price_paddle_id: price.paddle_id });
        currentSubscription.value = response.subscription;
        await fetchSubscriptions(); // Refresh the subscription data
    } catch (err) {
        error.value = err.response?.data?.error || 'An error occurred while changing the plan';
    }
};

const cancelCurrentSubscription = async () => {
    try {
        const response = await cancelSubscription(currentSubscription.value.id);
        currentSubscription.value = response.subscription;
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

    if (props.user.paddle_id && !subscriptionData.value.subscription_id) {
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
