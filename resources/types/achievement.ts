import { Image } from '@/types/image'

export type Achievement = {
    icon: Image
    type: string
    time: number
    percent: number
    color: string

    title: string
    description: string
    details: Array<string> | null
}
