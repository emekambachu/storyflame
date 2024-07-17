import { defineStore } from 'pinia'
import User from '../types/user'
import { computed, ref } from 'vue'
import axios from 'axios'
import { SuccessResponse } from '../types/responses'
import { getAuthenticatedUser } from '@/utils/endpoints'

const user = ref<User | null>(null)

export const useAuthStore = defineStore(
    'auth',
    () => {
        const isLoggedIn = computed(() => !!user.value)

        const federate = async (email: string, referral_code?: string) => {
            const { data } = await axios.post<
                SuccessResponse<{
                    pwd: boolean
                    otp_sent: boolean
                    email: string
                    email_verified_at: string | null
                    referred_by: string | null
                }>
            >('/api/v1/auth/federate', { email, referral_code })
            return data
        }

        const login = async (credentials: {
            email: string
            password: string
            otp: string
            referral_code?: string
        }) => {
            await axios.get('/sanctum/csrf-cookie')
            const { data } = await axios.post<SuccessResponse<User>>(
                '/api/v1/auth/login',
                credentials
            )
            user.value = data.data
            return data
        }

        const register = async (credentials: {
            name: string
            email: string
            password: string
        }) => {
            await axios.get('/sanctum/csrf-cookie')
            const { data } = await axios.post<SuccessResponse<User>>(
                '/api/v1/auth/register',
                credentials
            )
            user.value = data.data
            return data
        }

        const logout = async () => {
            const { data } = await axios.post<SuccessResponse<null>>(
                '/api/v1/auth/logout'
            )
            user.value = null
            return data
        }
        const getUser = async () => {
            // if getAuthenticatedUser returns 401, then return null
            const response = await getAuthenticatedUser()
            if (!response.data) {
                user.value = null
                return null
            }
            user.value = response.data
            return response.data
        }

        const updateUser = (data: User) => {
            user.value = data
        }

        return {
            user,
            isLoggedIn,
            register,
            federate,
            login,
            logout,
            getUser,
            updateUser,
        }
    },
    {
        persist: true,
    }
)
