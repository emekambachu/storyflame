import { defineStore } from 'pinia'
import User from '../types/user'
import { computed, ref } from 'vue'
import axios from 'axios'
import { SuccessResponse } from '../types/responses'

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null)

    const isLoggedIn = computed(() => !!user.value)

    const login = async (credentials: { email: string, password: string }) => {
        await axios.get('/sanctum/csrf-cookie')
        const { data } = await axios.post<SuccessResponse<User>>('/api/v1/auth/login', credentials)
        user.value = data.data
        return data
    }

    const register = async (credentials: { name: string, email: string, password: string }) => {
        await axios.get('/sanctum/csrf-cookie')
        const { data } = await axios.post<SuccessResponse<User>>('/api/v1/auth/register', credentials)
        user.value = data.data
        return data
    }

    const logout = async () => {
        const { data } = await axios.post<SuccessResponse<null>>('/api/v1/auth/logout')
        user.value = null
        return data
    }
    const getUser = async () => {
        const { data } = await axios.get<SuccessResponse<User>>('/api/v1/auth/user')
        user.value = data.data
        return data
    }

    return {
        user,
        isLoggedIn,
        register,
        login,
        logout,
        getUser,
    }
}, {
    persist: true,
})
