import { getAuthenticatedUser } from '@/utils/endpoints'
import { useQuery } from '@tanstack/vue-query'

export function useUser() {
    return useQuery({
        queryKey: ['user'],
        queryFn: async () => {
            const response = await getAuthenticatedUser();
            console.log('Query Function Response:', response.data); // Log the query function response
            return response;
        },
        select: (data) => {
            console.log('Selected Data:', data.data); // Log the selected data
            return data.data;
        },
    });
}
