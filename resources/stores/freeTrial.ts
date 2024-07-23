// @/stores/freeTrial.ts
import { defineStore } from 'pinia';
import axios from 'axios';

export const useFreeTrialStore = defineStore('freeTrial', {
    state: () => ({
        totalRemainingInteractions: 50,
        dailyRemainingInteractions: 25,
        imageGenerationsRemaining: 25,
    }),
    actions: {
        async fetchRemainingInteractions() {
            try {
                const response = await axios.get('/api/free-trial/remaining-interactions');
                this.totalRemainingInteractions = response.data.total_remaining_interactions;
                this.dailyRemainingInteractions = response.data.daily_remaining_interactions;
                this.imageGenerationsRemaining = response.data.image_generations_remaining;
            } catch (error) {
                console.error('Error fetching remaining interactions:', error);
            }
        },
        async trackInteraction() {
            try {
                await axios.post('/api/free-trial/track-interaction');
                await this.fetchRemainingInteractions();
            } catch (error) {
                console.error('Error tracking interaction:', error);
            }
        },
        async trackImageGeneration() {
            try {
                const response = await axios.post('/api/free-trial/track-image-generation');
                return response.data.success;
            } catch (error) {
                console.error('Error tracking image generation:', error);
                return false;
            }
        },
    },
});
