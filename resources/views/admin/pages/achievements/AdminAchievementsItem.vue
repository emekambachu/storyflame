<script setup lang="ts">
import moment from 'moment';
import {ref, defineProps} from 'vue';

const props = defineProps([
    'achievement',
]);

const achievement = ref(props.achievement);
</script>

<template>
    <tr class="bg-white border-b">
        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
            <img :src="achievement.icon" width="60">
        </th>
        <td class="px-6 py-4 text-center">
            <p class="font-extrabold text-md text-black">{{ achievement.name }}</p>
            <p class="text-gray-400 text-md">ID: {{ achievement.item_id }}</p>
        </td>
        <td class="px-6 py-4 text-center">
            <div v-if="achievement.categories?.length > 0" class="d-flex">
                <span
                    v-for="category in achievement.categories"
                    :key="category.id"
                      class="bg-stone-200 p-2 mr-2 text-[12px] rounded-md text-black">
                    {{ category.name }}
                </span>
                <span class="mt-1">+3</span>
            </div>
        </td>
        <td class="px-6 py-4 text-center">
            {{ achievement.data_points?.length > 0 ? achievement.data_points.length : 0}}
        </td>
        <td class="px-6 py-4 text-center">
            {{ achievement.summaries?.length > 0 ? achievement.sumamries.length : 0}}
        </td>
        <td class="px-6 py-4 text-center">
            {{ achievement.dev_order }}
        </td>
        <td class="px-6 py-4 text-center">
            {{ achievement.impact_score }}
        </td>
        <td class="px-6 py-4 text-center">
            <p class="text-black">{{ achievement.updated_at }}</p>
            <p>{{ achievement.admin }}</p>
        </td>
        <td class="px-6 py-4 text-center flex">
            <svg class="mr-2"
                 xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                <g clip-path="url(#clip0_1940_1784)">
                    <path d="M8 10.5001L13.59 4.89007C13.8526 4.62751 14.0001 4.27139 14.0001 3.90007C14.0001 3.52875 13.8526 3.17264 13.59 2.91007C13.3274 2.64751 12.9713 2.5 12.6 2.5C12.2287 2.5 11.8726 2.64751 11.61 2.91007L6 8.50007V10.5001H8Z" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.6667 3.83333L12.6667 5.83333" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6.0006 5.21321C4.83113 5.37984 3.76836 5.98378 3.02668 6.9032C2.28499 7.82263 1.91959 8.98911 2.00417 10.1674C2.08876 11.3456 2.61703 12.448 3.48244 13.252C4.34785 14.0561 5.48598 14.502 6.66727 14.4999C7.78953 14.5 8.87425 14.0957 9.72265 13.3611C10.571 12.6264 11.1263 11.6106 11.2866 10.4999" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                    <clipPath id="clip0_1940_1784">
                        <rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
                    </clipPath>
                </defs>
            </svg>

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                <path d="M9.41421 9.91421C9.78929 9.53914 10 9.03043 10 8.5C10 7.96957 9.78929 7.46086 9.41421 7.08579C9.03914 6.71071 8.53043 6.5 8 6.5C7.46957 6.5 6.96086 6.71071 6.58579 7.08579C6.21071 7.46086 6 7.96957 6 8.5C6 9.03043 6.21071 9.53914 6.58579 9.91421C6.96086 10.2893 7.46957 10.5 8 10.5C8.53043 10.5 9.03914 10.2893 9.41421 9.91421Z" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M1.63867 8.5C2.48801 5.79533 5.01534 3.83333 8 3.83333C10.9853 3.83333 13.512 5.79533 14.3613 8.5C13.512 11.2047 10.9853 13.1667 8 13.1667C5.01534 13.1667 2.48801 11.2047 1.63867 8.5Z" stroke="#C1BCB8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </td>
    </tr>
</template>

<style scoped>

</style>
