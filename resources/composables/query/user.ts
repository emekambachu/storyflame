import { getAuthenticatedUser } from '@/utils/endpoints'
import { useQuery } from '@tanstack/vue-query'

export function useUser() {
    return useQuery({
        queryKey: ['user'],
        queryFn: getAuthenticatedUser,
        select: (data) => data.data,
    })
}
