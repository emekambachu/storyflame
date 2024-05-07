import { App, Component, markRaw, reactive } from 'vue'
import ModalContainer from '@/plugins/ModalContainer.vue'
import { mountComponent, MountedComponentInstance } from 'vue-mountable'

export type ModalDefinition = {
    id: string
    component: Component
    props: Record<string, any>
    open: boolean
}

export default {
    install: (app: App) => {
        let container: MountedComponentInstance | null = null
        const modals = reactive<ModalDefinition[]>([])
        const mountContainer = () => {
            if (container) return container
            container = mountComponent({
                component: ModalContainer,
                props: { modals },
            })
        }

        app.provide('modal', {
            show: (component: Component, props = {}) => {
                mountContainer()
                modals.push({
                    id: Math.random().toString(36).slice(2, 9),
                    component: markRaw(component),
                    props,
                    open: true,
                })
                document.body.classList.add('no-scroll')
            },
            close: (id: string | undefined = undefined) => {
                if (!id) {
                    modals.splice(0, modals.length)
                } else {
                    const index = modals.findIndex((m) => m.id === id)
                    if (index !== -1) {
                        modals.splice(index, 1)
                    }
                }
                if (modals.length === 0)
                    document.body.classList.remove('no-scroll')
            },
            modals,
        })
    },
}
