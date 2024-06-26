<template>
    <svg
        v-if="!icon"
        class="shrink-0"
        xmlns="http://www.w3.org/2000/svg"
        width="23"
        height="25"
        viewBox="0 0 23 25"
        fill="none"
    >
        <g filter="url(#filter0_d_2026_14381)">
            <path
                d="M9.21241 2.26795C9.83121 1.91068 10.5936 1.91068 11.2124 2.26795L18.3057 6.36325C18.9245 6.72051 19.3057 7.38077 19.3057 8.0953V16.2859C19.3057 17.0004 18.9245 17.6607 18.3057 18.018L11.2124 22.1132C10.5936 22.4705 9.83121 22.4705 9.21241 22.1132L2.11914 18.018C1.50034 17.6607 1.11914 17.0004 1.11914 16.2859V8.0953C1.11914 7.38077 1.50034 6.72051 2.11914 6.36325L9.21241 2.26795Z"
                :fill="color"
            />
        </g>
        <defs>
            <filter
                id="filter0_d_2026_14381"
                x="0.119141"
                y="0"
                width="22.1865"
                height="24.3809"
                filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB"
            >
                <feFlood
                    flood-opacity="0"
                    result="BackgroundImageFix"
                />
                <feColorMatrix
                    in="SourceAlpha"
                    type="matrix"
                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                    result="hardAlpha"
                />
                <feOffset dx="1" />
                <feGaussianBlur stdDeviation="1" />
                <feComposite
                    in2="hardAlpha"
                    operator="out"
                />
                <feColorMatrix
                    type="matrix"
                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.05 0"
                />
                <feBlend
                    mode="normal"
                    in2="BackgroundImageFix"
                    result="effect1_dropShadow_2026_14381"
                />
                <feBlend
                    mode="normal"
                    in="SourceGraphic"
                    in2="effect1_dropShadow_2026_14381"
                    result="shape"
                />
            </filter>
        </defs>
    </svg>
    <img
        v-else
        :src="icon"
        class="shrink-0 object-contain"
    />
</template>

<script setup lang="ts">
const props = defineProps({
    icon: {
        type: String,
        default: null,
    },
    color: {
        type: String,
        default: '#363432',
    },
})
</script>

<style scoped></style>
