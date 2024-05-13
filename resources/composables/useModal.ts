import { Component, inject } from 'vue'

export default function useModal() {
    const { show: showModal, close: closeModal } = inject<any>('modal')

    return {
        show: (component: Component | string, props = {}): string => {
            return showModal(component, props)
        },

        close: (id: string | undefined = undefined): void => {
            closeModal(id)
        },
    }
}
