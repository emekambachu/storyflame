<template>
    <div>
        <h1>Home</h1>
        <template v-if="!auth.isLoggedIn">
            <button @click="register">
                register
            </button>
            <button @click="login">
                login
            </button>
        </template>
        <template v-else>
            <button @click="user">
                me
            </button>
            <button @click="logout">
                logout
            </button>
        </template>

        <pre>
            {{ auth.isLoggedIn }}
            {{ auth.user }}
        </pre>
    </div>
</template>

<script lang="ts" setup>
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()

function login() {
    auth.login({
        email: 'nazar@mutado.dev',
        password: 'moonpass',
    })
}

function register() {
    axios.post('/api/v1/auth/register', {
        name: 'Nazar',
        email: 'nazar@mutado.dev',
        password: 'moonpass',
    })
}

function user() {
    axios.get('/api/v1/auth/user').then(response => {
        console.log(response.data)
    })
}

function logout() {
    axios.post('/api/v1/auth/logout').then(response => {
        console.log(response.data)
    })
}
</script>
