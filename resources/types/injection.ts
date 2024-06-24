import { InjectionKey, Ref } from 'vue'
import { Tab } from '@/types/layout'

export const tabLayoutTabsInjection = Symbol('tab-layout-tabs') as InjectionKey<
    Array<Tab>
>
export const tabLayoutActiveTabInjection = Symbol(
    'tab-layout-active-tab'
) as InjectionKey<Ref<string>>
