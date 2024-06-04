import { defineStore } from 'pinia'
import { reactive } from 'vue'

export const usePagesStore = defineStore('pages', () => {
    const transition = reactive({
        name: 'none',
        mode: 'in-out',
    })

    const setTransition = (name: string) => {
        console.log('setTransition', name)
        if (name === 'default') {
            transition.name = 'none'
            transition.mode = 'in-out'
        } else if (name === 'slide') {
            transition.name = 'slide'
            transition.mode = 'in-out'
        } else if (name === 'back') {
            transition.name = 'slide-back'
            transition.mode = ''
        }
    }

    return {
        transition,
        setTransition,
    }
})
