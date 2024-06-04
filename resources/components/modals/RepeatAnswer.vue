<template>
    <modal-popup
        primary="Repeat my answer"
        secondary="Write it with text"
    >
        <div class="flex flex-col items-center text-center gap-2">
            <span class="text-[50px]">😢</span>
            <h1 class="font-bold text-2xl">Oops</h1>
            <p class="text-[#3C3C3C] text-base">
                Sorry, we couldn't understand your answer, could you please
                repeat it?
            </p>
        </div>
    </modal-popup>
</template>

<script lang="ts" setup>
import ModalPopup from '@/components/modals/ModalPopup.vue'
</script>

<style scoped></style>
