<script setup lang="ts">
import { defineProps } from 'vue';
const props = defineProps({
    width: {
        type: String,
        default: '20px',
    },
    height: {
        type: String,
        default: '20px',
    },
    fill: {
        type: String,
        default: 'none',
    },
});
</script>

<template>
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
</template>

<style scoped>

</style>
