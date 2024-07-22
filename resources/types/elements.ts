export type StoryElementKey = 'characters' | 'sequences' | 'themes' | 'appeal' | 'episodes'
export type ElementKey = StoryElementKey

export type ElementProgress = {
    progress: number
    count: number
    elements: Array<any>
}
