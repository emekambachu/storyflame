<template>
    <div
        class="max-w-[540px] h-full mx-auto mt-1 font-normal bg-white text-black flex flex-col gap-4 items-center"
    >
        <div class="relative w-full">
            <a
                class="absolute text-neutral-950 top-auto bottom-auto left-0"
                href="/"
            >
                <chevron />
            </a>
            <h2 class="text-base font-normal text-center text-neutral-950">
                Register
            </h2>
        </div>
        <form
            class="w-full flex flex-col gap-3 h-full"
            @submit.prevent="register"
        >
            <div>
                <label
                    class="text-slate-400 text-sm font-bold"
                    for="email"
                >
                    email
                    <input
                        id="email"
                        v-model="email"
                        class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your email"
                        required
                        type="email"
                    />
                </label>
            </div>

            <div class="mt-auto mb-0 flex flex-col gap-5">
                <button
                    class="w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-red-600 hover:bg-red-700"
                    type="submit"
                >
                    Register
                </button>
                <p class="inline text-neutral-950 text-sm font-normal">
                    Already have an account?
                    <router-link
                        :to="{ name: 'login' }"
                        class="text-red-600"
                    >
                        Login
                    </router-link>
                </p>
                <p class="inline text-neutral-950 text-sm font-normal">
                    By continuing, you agree to our
                    <a
                        class="text-red-600"
                        href="/service-terms"
                    >
                        Terms of Service
                    </a>
                    and
                    <a
                        class="text-red-600"
                        href="/privacy-police"
                    >
                        Privacy Policy
                    </a>
                    .
                </p>
            </div>
        </form>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import Chevron from '../components/icons/Chevron.vue'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const name = ref('')
const email = ref('')
const password = ref('')
const router = useRouter()

function register() {
    auth.register({
        name: name.value,
        email: email.value,
        password: password.value,
    }).then(() => {
        router.push({ name: 'onboarding' })
    })
}
</script>

<style scoped></style>
