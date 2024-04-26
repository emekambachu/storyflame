<template>
  <div class="w-full h-[84vh] text-black px-4 flex flex-col gap-4">
    <h1 class="font-bold text-2xl text-black">Home</h1>
    <div
      v-if="!auth.isLoggedIn"
      class="flex items-center gap-3"
    >
      <a
        class="rounded-full bg-red-600 text-white px-6 py-2 hover:bg-red-700"
        href="/auth/register"
      >
        register
      </a>
      <a
        class="rounded-full bg-red-600 text-white px-6 py-2 hover:bg-red-700"
        href="/auth/login"
      >
        login
      </a>
    </div>
    <div
      v-else
      class="flex items-center gap-3"
    >
      <button @click="user">me</button>
      <button @click="logout">logout</button>
    </div>

    <pre>
              {{ auth.isLoggedIn }}
              {{ auth.user }}
      </pre
    >
  </div>
</template>

<script lang="ts" setup>
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()

function login() {
  auth.login({
    email: 'nazar@mutado.dev',
    password: 'moonpass',
  })
}

function register() {
  auth.register({
    name: 'Nazar',
    email: 'nazar@mutado.dev',
    password: 'moonpass',
  })
}

function user() {
  auth.getUser().then((response) => {
    console.log(response.data)
  })
}

function logout() {
  auth.logout().then((response) => {
    console.log(response.data)
  })
}
</script>
