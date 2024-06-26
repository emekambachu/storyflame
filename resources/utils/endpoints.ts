import api from '@/utils/api'
import User from '@/types/user'
import Story from '@/types/story'

export function getOnboardingSummary() {
    return api.get<User>('/api/v1/onboarding/summary')
}

export function getAuthenticatedUser() {
    return api.get<User>('/api/v1/auth/user')
}

export function getStories() {
    return api.get<Array<Story>>('/api/v1/stories')
}

export function getStory(id: string) {
    return api.get<Story>(`/api/v1/stories/${id}`)
}

export function getSequence(storyId: string, id: string) {
    return api.get<Sequence>(`/api/v1/stories/${storyId}/sequences/${id}`)
}
