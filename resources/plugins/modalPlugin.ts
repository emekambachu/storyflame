import { App, Component, InjectionKey, markRaw, reactive } from 'vue'
import ModalContainer from '@/plugins/ModalContainer.vue'
import { mountComponent, MountedComponentInstance } from 'vue-mountable'
import FullScreenLoader from '@/components/modals/FullScreenLoader.vue'
import RepeatAnswer from '@/components/modals/RepeatAnswer.vue'

export type ModalEventCallback = (
    payload: any[],
    close: (force?: boolean) => void,
    modal: ModalDefinition
) => void

export type ModalDefinition = {
    id: string
    component: Component
    props: Record<string, any>
    events: Record<string, ModalEventCallback>
    open: boolean
}

export const modalInjectKey = Symbol() as InjectionKey<{
    show: (
        component: Component | string,
        props?: Record<string, any>,
        events?: Record<string, ModalEventCallback>
    ) => string
    close: (id?: string, force?: boolean) => void
    modals: ModalDefinition[]
}>

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
            'repeat-answer': {
                component: () => RepeatAnswer,
            },
        }

        const show = (
            component: Component | string,
            props = {},
            events = {}
        ): string => {
            mountContainer()
            const id = Math.random().toString(36).slice(2, 9)

            if (typeof component === 'string') {
                const defaultComponent = defaultComponents[component]
                if (!defaultComponent) {
                    throw new Error(
                        `Default component "${component}" not found`
                    )
                }
                component = defaultComponent.component()
                props = defaultComponent.props
            }

            modals.push({
                id,
                component: markRaw(component as Component),
                props,
                events: events,
                open: true,
            })
            document.body.classList.add('no-scroll')
            return id
        }

        const close = (id: string | undefined = undefined, force = false) => {
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
        }

        app.provide(modalInjectKey, {
            show,
            close,
            modals,
        })
    },
}
