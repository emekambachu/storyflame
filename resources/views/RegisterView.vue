<template>
    <div
        class="max-w-[540px] py-8 min-h-screen mx-auto font-normal bg-white text-black flex flex-col gap-10 items-center justify-between"
    >
        <a href="/">
            <img
                src="@/assets/logo.svg"
                class="w-[393px]"
            />
        </a>

        <div class="flex flex-col gap-8 w-full">
            <div class="flex flex-col gap-4 items-center">
                <h5 class="inline text-center text-black text-lg font-semibold">
                    Develop your story.
                    <br />
                    Refine your story with fire 🔥
                </h5>
                <span class="text-zinc-400 text-sm font-normal">
                    Please, enter your email to register
                </span>
            </div>

            <label
                class="text-black text-base font-bold"
                for="email"
            >
                <input
                    id="email"
                    v-model="email"
                    class="mt-1 bg-white font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none"
                    placeholder="Enter your email"
                    required
                    type="email"
                />
            </label>
        </div>

        <div class="mt-auto mb-0 flex flex-col gap-5">
            <button
                @click="register"
                class="w-full flex justify-center py-4 px-4 rounded-full text-base font-semibold text-white bg-orange-600 hover:bg-orange-600"
                type="submit"
            >
                Register
            </button>
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
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import Chevron from '../components/icons/ChevronIcon.vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

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
