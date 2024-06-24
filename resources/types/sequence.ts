export type Sequence = {
    id: string
    name: string
}

export const mockSequence: Sequence = {
    id: 'test',
    name: 'Test sequence',
    story_id: 'test',
    description: 'This is a test sequence',
    type: 'Action',
    genres: ['Action', 'Comedy'],
}
