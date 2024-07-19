<template>
    <router-link
        :to="{ name: 'profile' }"
        class="flex h-8 w-8 shrink-0 rounded-full overflow-hidden bg-stone-200 text-stone-600 font-semibold"
    >
        <div v-if="avatarUrl"
             class="w-full h-full bg-cover bg-center"
             :style="{ backgroundImage: `url(${avatarUrl})` }">
        </div>
        <div v-else-if="initials"
             class="w-full h-full flex items-center justify-center hover:bg-stone-300 hover:text-stone-900">
            {{ initials }}
        </div>
        <div v-else
             class="w-full h-full flex items-center justify-center hover:bg-stone-300 hover:text-stone-900">
            <WriterIcon class="w-3 h-3" />
        </div>
    </router-link>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import WriterIcon from "@/components/icons/WriterIcon.vue";

const auth = useAuthStore();

const avatarUrl = computed(() => auth.user?.avatar || null);

const initials = computed(() => {
    const firstName = auth.user?.first_name;
    const lastName = auth.user?.last_name;

    if (firstName && lastName) {
        return `${firstName[0]}${lastName[0]}`.toUpperCase();
    } else if (firstName) {
        return firstName[0].toUpperCase();
    }

    return null;
});
</script>
