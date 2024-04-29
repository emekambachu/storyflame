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
      @submit.prevent="register"
      class="w-full flex flex-col gap-3 h-full"
    >
      <div>
        <label
          for="email"
          class="text-slate-400 text-sm font-bold"
        >
          email
          <input
            type="email"
            id="email"
            v-model="email"
            class="mt-1 font-normal text-xl text-neutral-950 block w-full px-3 py-3 border-b border-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
            placeholder="Enter your email"
            required
          />
        </label>
      </div>

      <div class="mt-auto mb-0 flex flex-col gap-5">
        <button
          type="submit"
          class="w-full flex justify-center py-4 px-4 rounded-full text-sm font-semibold text-white bg-red-600 hover:bg-red-700"
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
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import Chevron from '../components/icons/Chevron.vue'

const auth = useAuthStore()
const name = ref('')
const email = ref('')
const password = ref('')

function register() {
  auth.register({
    name: name.value,
    email: email.value,
    password: password.value,
  })
}
</script>

<style scoped></style>
