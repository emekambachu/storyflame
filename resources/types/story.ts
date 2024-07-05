import { Image } from '@/types/image'

export type Story = {
    id: string
    name: string
    description: string
    type: string
    image: Image|null
    format: string
    genres: Array<string>
    percent: number
    goals: Array<string>
    market_comps: Array<{
        title: string
        image: Image | null
    }>
}

export const mockStory: Story = {
    id: 'test',
    name: 'Test story',
    image: {
        path: 'https://placekeanu.com/600/400',
    },
    description:
        'This is a test story about some hero saving the world from some evil villain. First he struggles, then he realizes someting and then he wins. The end.',
    type: 'Movie',
    genres: ['Action', 'Comedy'],
    format: 'Animated, 3D',
    percent: 25,
    goals: [
        'Create a main hero',
        'Create a villain',
        'Create a sidekick',
        'Create a world',
    ],
    market_comps: [
        {
            title: 'The Incredibles',
            image: {
                path: 'https://placekeanu.com/450',
            },
        },
        {
            title: 'Despicable Me',
            image: {
                path: 'https://placekeanu.com/300',
            },
        },
        {
            title: 'Despicable Me',
            image: null,
        },
    ],
    progress: [
        {
            name: 'Characters',
            progress: 1,
        },
        {
            name: 'Sequences',
            progress: 2,
        },
        {
            name: 'Themes',
            progress: 3,
        },
        {
            name: 'Appeal',
            progress: 4,
        },
    ],

    characters: [
        {
            role: 'Protagonist',
            name: 'Daenerys Targaryen',
            types: ['Helpless Sibling', 'Zero to Hero'],
            description: 'Gain independence and reclaim her ancestral throne.',
        },
        {
            role: 'Protagonist',
            name: 'Daenerys Targaryen',
            types: ['Helpless Sibling', 'Zero to Hero'],
            description: 'Gain independence and reclaim her ancestral throne.',
        },
    ],
    target_audience: [
        {
            title: 'Fantasy Enthusiast',
            description:
                'Immersed in fantasy worlds, interested in medieval history, politics, and warfare.',
            prefer: 'Prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
            dislike:
                'Dislikes overly simplistic or clichéd fantasy tropes, prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
        },
        {
            title: 'Fantasy Enthusiast',
            description:
                'Immersed in fantasy worlds, interested in medieval history, politics, and warfare.',
            prefer: 'Prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
            dislike:
                'Dislikes overly simplistic or clichéd fantasy tropes, prefers narratives that subvert genre conventions and challenge traditional hero archetypes.',
        },
    ],
    impactful_scenes: [
        {
            id: '1',
            priority: 1,
            name: 'Red Wedding',
            description:
                'A massacre at the wedding of Edmure Tully and Roslin Frey orchestrated by Walder Frey and Roose Bolton, leading to the deaths of key Stark family members.',
        },
        {
            id: '21',
            priority: 2,
            name: 'Red Wedding',
            description:
                'A massacre at the wedding of Edmure Tully and Roslin Frey orchestrated by Walder Frey and Roose Bolton, leading to the deaths of key Stark family members.',
        },
    ],
    drafts: [],
}
