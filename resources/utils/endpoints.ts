import api from '@/utils/api'
import User from '@/types/user'
import { Story } from '@/types/story'
import { Sequence } from '@/types/sequence'

export function getOnboardingSummary() {
    return api.get<User>('/api/v1/onboarding/summary')
}

export async function getAuthenticatedUser() {
    try {
        const response = await api.get<User>('/api/v1/auth/user');
        console.log('API Response:', response.data); // Log the API response
        return response;
    } catch (error) {
        console.error('API Error:', error); // Log any errors
        throw error; // Rethrow the error to be handled by the query function
    }
}

export function getStories() {
    return api.get<Array<Story>>('/api/v1/stories')
}

export function getStory(id: number) {
    return api.get<Story>(`/api/v1/stories/${id}`)
}

export function getSequence(storyId: string, id: string) {
    return api.get<Sequence>(`/api/v1/stories/${storyId}/sequences/${id}`)
}

export const getSubscriptions = async () => {
    const response = await api.get('/api/v1/subscriptions');
    return response.response.data;
};

export const changeSubscription = async (id, productPricePaddleId) => {
    const response = await api.put(`/api/v1/subscriptions/${id}`, { productPricePaddleId });
    return response.data;
};

export const cancelSubscription = async (id) => {
    const response = await api.delete(`/api/v1/subscriptions/${id}`);
    return response.data;
};

export const getInvoices = async () => {
    const response = await api.get('/api/v1/subscriptions/invoices');
    return response.response.data;
};

export const getInvoiceLink = async (id) => {
    const response = await api.get(`/api/v1/subscriptions/invoices/${id}`);
    return response.response.data;
}
export const createCustomer = async () => {
    const response = await api.post('/api/v1/subscriptions/customer');
    return response.response.data;
}
