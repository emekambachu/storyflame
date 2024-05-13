import { InjectionKey } from 'vue'

export const uaInjectKey = Symbol() as InjectionKey<{
    agent: string
    isMobile: boolean
}>
