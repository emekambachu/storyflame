<template>
    <!-- <nav class="bg-white shadow-lg text-slate-500 p-4">nav</nav> -->
    <section class="bg-white h-screen flex flex-col">
        <router-view
            :auth_user="auth_user"
        ></router-view>
    </section>
    <!-- <footer class="bg-white shadow-lg text-slate-500 p-4">footer</footer> -->
</template>

<script lang="ts" setup>
import { defineProps } from 'vue';

const props = defineProps({
   auth_user: {
     type: Object,
     required: true,
   }
});

</script>

<style scoped>
.slide-enter-active,
.slide-leave-active,
.slide-back-enter-active,
.slide-back-leave-active {
    position: fixed;
    width: 100%;
    min-height: 100vh;
    top: 0;
}

.slide-enter-active {
    transition: transform 0.25s ease-in-out;
    z-index: 20;
    transform: translateX(100%);
}

.slide-enter-to {
    z-index: 20;
    transform: translateX(0);
}

/**
.slide-leave-active {
    transition: all 0.25s ease-in-out;
    z-index: 2;
}

.slide-leave-to {
    filter: brightness(0.75);
}

.slide-back-enter-active {
    transition: all 0.25s ease-in-out;
}
.slide-back-enter-from {
    filter: brightness(0.75)
}
**/

.slide-leave-to,
.slide-leave-active {
    z-index: -1;
}

.slide-back-leave-active {
    transition: transform 0.25s ease-in-out;
    z-index: 20;
    transform: translateX(0%);
}

.slide-back-leave-to {
    z-index: 20;
    transform: translateX(100%);
}
</style>
