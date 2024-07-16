<template>
    <div class="flex flex-col gap-6 w-full mb-auto mt-0">
        <div>
            <label class="text-black text-base font-bold w-full" for="current-password">
                Current Password
            </label>
            <div class="relative">
                <input
                    v-model="form.current_password"
                    :type="showCurrentPassword ? 'text' : 'password'"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="Enter your current password"
                    required
                />
                <button
                    class="absolute right-0 top-0 mt-3 mr-3 focus:outline-none"
                    @click.prevent="showCurrentPassword = !showCurrentPassword"
                >
                    <crossed-eye v-if="!showCurrentPassword" />
                    <open-eye v-else />
                </button>
            </div>
        </div>

        <div>
            <label class="text-black text-base font-bold w-full" for="new-password">
                New Password
            </label>
            <div class="relative">
                <input
                    v-model="form.newPassword"
                    :type="showNewPassword ? 'text' : 'password'"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="Enter your new password"
                    required
                />
                <button
                    class="absolute right-0 top-0 mt-3 mr-3 focus:outline-none"
                    @click.prevent="showNewPassword = !showNewPassword"
                >
                    <crossed-eye v-if="!showNewPassword" />
                    <opened-eye
                        :height="'24px'"
                        :width="'24px'"
                        :color="showNewPassword ? 'text-orange-600' : 'text-neutral-700'"
                        v-else />
                </button>
            </div>
        </div>

        <div>
            <label class="text-black text-base font-bold w-full" for="confirm-password">
                Confirm New Password
            </label>
            <div class="relative">
                <input
                    v-model="form.confirmPassword"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="Confirm your new password"
                    required
                />
                <button
                    class="absolute right-0 top-0 mt-3 mr-3 focus:outline-none"
                    @click.prevent="showConfirmPassword = !showConfirmPassword"
                >
                    <crossed-eye v-if="!showConfirmPassword" />
                    <open-eye v-else />
                </button>
            </div>
        </div>

        <div class="flex flex-col items-center w-full gap-2 mt-auto mb-0">
            <button
                class="w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700"
                type="submit"
                @click.prevent="updatePassword"
            >
                Update Password
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import CrossedEye from '../../../components/icons/CrossedEye.vue'
import OpenedEye from '@/components/icons/OpenedEye.vue'

const form = reactive({
    current_password: '',
    new_password: '',
    confirm_new_password: ''
})

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)

function updatePassword() {
    // Logic to update the password
}
</script>
