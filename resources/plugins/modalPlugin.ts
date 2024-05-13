import { App, Component, markRaw, reactive } from 'vue'
import ModalContainer from '@/plugins/ModalContainer.vue'
import { mountComponent, MountedComponentInstance } from 'vue-mountable'
import FullScreenLoader from '@/components/modals/FullScreenLoader.vue'

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

        const defaultComponents = {
            'full-screen-loader': {
                component: () => FullScreenLoader,
                props: {
                    type: 'full',
                    backdrop: false,
                },
            },
        }

        app.provide('modal', {
            show: (component: Component | string, props = {}): string => {
                mountContainer()
                const id = Math.random().toString(36).slice(2, 9)

                if (typeof component === 'string') {
                    const defaultComponent = defaultComponents[component] as any
                    if (!defaultComponent) {
                        throw new Error(
                            `Default component "${component}" not found`
                        )
                    }
                    component = defaultComponent.component()
                    props = defaultComponent.props
                }

                console.log(component, props)

                modals.push({
                    id,
                    component: markRaw(component as Component),
                    props,
                    open: true,
                })
                document.body.classList.add('no-scroll')
                return id
            },
            close: (id: string | undefined = undefined, force = false) => {
                console.log('close', id, force)
                if (force) {
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
                } else {
                    if (!id) {
                        const modal = modals[modals.length - 1]
                        if (modal) {
                            modal.open = false
                        }
                    } else {
                        const modal = modals.find((m) => m.id === id)
                        if (modal) {
                            modal.open = false
                        }
                    }
                }
            },
            modals,
        })
    },
}
