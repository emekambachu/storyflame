<template>
    <div v-if="!isLoading">
        <h2>Current</h2>
        <div class="subscription-card">
            <div v-if="currentPlan" class="subscription-header">
                <h3>{{ currentPlan.name }}</h3>
                {{ currentPlan.price === 0 ? 'Free' : currentPlan.price }}
            </div>
            <ul class="subscription-features">
                <li v-for="feature in currentPlan.features" :key="feature">
                    {{ feature }}
                </li>
            </ul>
        </div>

        <<h2>Change</h2>
        <div class="subscription-toggle">
            <button @click="toggleBilling('annual')" :class="{ active: billingCycle === 'annual' }">Annual save 20%</button>
            <button @click="toggleBilling('monthly')" :class="{ active: billingCycle === 'monthly' }">Monthly</button>
        </div>

        <div v-for="plan in plans" :key="plan.name" class="subscription-card">
            <div class="subscription-header">
                <h3>{{ plan.name }}</h3>
                <span>{{ getPrice(plan) }}</span>
                <button @click="selectPlan(plan.name)">Select</button>
            </div>
            <ul class="subscription-features">
                <li v-for="feature in plan.features" :key="feature">
                    {{ feature }}
                </li>
            </ul>
        </div>

        <h2>Invoices</h2>
        <div v-for="invoice in invoices" :key="invoice.id" class="invoice-item">
            <span>{{ invoice.date }}</span>
            <div
                @click="loadInvoiceLinkInNewTab(invoice.transaction_id)"
                class="cursor-pointer"
            >View</div>
        </div>
    </div>
    <div v-else>
        <p>Loading...</p>
    </div>
    <div v-if="error">
        <p class="error">{{ error }}</p>
    </div>
</template>

<script lang="ts" setup>
import {computed, ref, onMounted, PropType} from 'vue';
import { createCustomer, getSubscriptions, changeSubscription, cancelSubscription, getInvoices, getInvoiceLink } from '@/utils/endpoints';
import User from "@/types/user.js";

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
});

const isLoading = ref(true);
const currentPlan = ref({});
const plans = ref([]);
const invoices = ref([]);
const billingCycle = ref('monthly');
const error = ref<string | null>(null);

const fetchSubscriptions = async () => {
    const response = await getSubscriptions();
    const subscriptions = response.subscriptions[0] || {}; // Assuming the first subscription as the current plan
    plans.value = response.plans;

    // get the first plan in the plans.value array as the current plan
    currentPlan.value = subscriptions;
    isLoading.value = false;
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

const toggleBilling = (cycle) => {
    billingCycle.value = cycle;
};

// const openCheckout = async () => {
//     paddle.Checkout.open({
//         product: "pri_01j2798w4jks9xccmxcg95ktkf",
//         email: props.user.email,
//     });
// };

const getPrice = (plan) => {
    console.log('the plan is ', plan);
    console.log('the prices are ', plan.prices);
    const price = plan.prices.find((price) => price.type === billingCycle.value);
    return price ? `$${price.price} per month` : 'N/A';
};

const selectPlan = async (plan) => {
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

    if (props.user.paddle_id) {
        Paddle.Checkout.open({
            items: [
                {
                    price_id: "pip install code2prompt\n",
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
