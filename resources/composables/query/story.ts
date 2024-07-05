import { useQuery } from '@tanstack/vue-query'
import { getStories, getStory } from '@/utils/endpoints'

export function useStories() {
    return useQuery({
        queryKey: ['stories'],
        queryFn: getStories,
        select: (data) => data.data,
    })
}

export function useStory(id: number) {
    return useQuery({
        queryKey: ['story', id],
        queryFn: () => getStory(id),
        select: (data) => data.data,
    })
}
