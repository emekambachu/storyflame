<template>
    <div>
        <h1>Home</h1>
        <button @click="register">
            register
        </button>
        <button @click="login">
            login
        </button>
        <button @click="user">
            me
        </button>
        <button @click="logout">
            logout
        </button>
    </div>
</template>

<script lang="ts" setup>
import axios from 'axios'

function login() {
    axios.post('/api/v1/auth/login', {
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
