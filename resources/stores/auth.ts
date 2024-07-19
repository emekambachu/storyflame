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

        const federate = async (email: string, referral_code?: string, use_code?: boolean) => {
            const { data } = await axios.post<
                SuccessResponse<{
                    pwd: boolean
                    otp_sent: boolean
                    email: string
                    email_verified_at: string | null
                    referred_by: string | null
                }>
            >('/api/v1/auth/federate', { email, referral_code, use_code })
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
            try {
                const response = await getAuthenticatedUser()
                if (response && response.data) {
                    user.value = response.data
                    return response.data
                } else {
                    user.value = null
                    return null
                }
            } catch (error) {
                console.error('Error fetching user:', error)
                user.value = null
                return null
            }
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
