<script setup lang="ts">

import Alert from '@/components/forms/Alert.vue'

const props = defineProps({
    errors: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <Alert v-if="errors.role" class="text-center p-2 bg-rose-100 border-rose-400 text-rose-700">
        {{ errors.role[0] }}
    </Alert>

    <Alert v-if="errors.error_message" class="text-center p-2 bg-rose-100 border-rose-400 text-rose-700">
        {{ errors.error_message[0] }}
    </Alert>

    <Alert v-if="errors.server_error" class="text-center p-2 bg-rose-100 border-rose-400 text-rose-700">
        {{ errors.server_error[0] }}
    </Alert>
</template>

<style scoped>

</style>
