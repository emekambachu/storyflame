<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue'
import axios from 'axios'

import ReferralTypeList from '@/views/admin/pages/referral-types/ReferralTypeList.vue'
import ReferralTypeListItem from '@/views/admin/pages/referral-types/ReferralTypeListItem.vue'
import baseService from '@/utils/base-service'
import store from '@/store'

const token = computed(() => baseService.getTokenFromLocalStorage();

// Slide over
const isSlideoverOpen = ref(false);
const openSlideover = () => {
    isSlideoverOpen.value = true;
};
const closeSlideover = () => {
    isSlideoverOpen.value = false;
};

const emittedType = (event) => {
    // referralTypes.value.unshift(event);
    total.value++;
    // closeSlideover();
}

const loading = ref(false);

const referralTypes = ref([]);
const total = ref(0);

const getReferrals = async () => {
    loading.value = true;
    await axios.get('/api/admin/referral-types', {
        // withCredentials: true,
        headers: {
            "Authorization" : "Bearer " + user.value.token,
            'Accept' : 'application/json',
        },
    }).then((response) => {
        if(response.data.success === true){
            summaries.value = response.data.summaries;
            total.value = response.data.total;
        }else{
            console.log(summaries.value);
        }
    }).catch((error) => {
        console.log(error);
    });
}

onBeforeMount(() => {
    getSummaries();
    store.dispatch('getData', {
        url: '/api/categories',
        commit_name: 'SET_CATEGORIES',
    });

    store.dispatch('getData', {
        url: '/api/admin/datapoints/min',
        commit_name: 'SET_MIN_DATA_POINTS',
    });

    store.dispatch('getData', {
        url: '/api/admin/summaries/min',
        commit_name: 'SET_MIN_SUMMARIES',
    });
});

</script>

<template>
    <div>
        <div class="flex justify-between items-center mt-4">
            <h2 class="text-2xl font-bold">Summaries ({{ total }})</h2>
            <button @click="openSlideover" class="px-4 py-2 bg-black text-white rounded-full">Create New Summary</button>
        </div>

        <AdminSummariesForm
            :isOpen="isSlideoverOpen"
            @close="closeSlideover"
            @formSubmitted="emittedSummary"
        >
        </AdminSummariesForm>

        <div class="relative overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-stone-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">
                        Name & ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Location
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Categories
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Data Points Count
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Linked Summaries
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Last Updated
                    </th>
                    <th scope="col" class="px-6 py-3">

                    </th>
                </tr>
                </thead>
                <tbody>
                <admin-summaries-item
                    v-for="summary in summaries"
                    :key="summary.id"
                    :summary="summary"
                />
                </tbody>
            </table>
        </div>


    </div>
</template>

<style scoped>

</style>
