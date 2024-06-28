import { defineStore } from 'pinia'
import { ref } from 'vue'
import { mockStory } from '@/types/story'

export const useMockStore = defineStore(
    'mock',
    () => {
        const groups = [
            {
                name: 'User mocks',
                mocks: {
                    'onboarded user': {
                        url: '/api/v1/auth/user',
                        method: 'GET',
                        response: {
                            id: 'mocked-id',
                            name: 'John Doe',
                            email: 'johndoe@example.com',
                            avatar: {
                                path: 'https://placekeanu.com/300',
                            },
                            achievements: [
                                {
                                    id: '9c2d0574-ff88-44a8-9dca-00908b4d262b',
                                    name: 'Symbolism and Associations',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Symbolism and Associations',
                                    description:
                                        'Imbuing characters with layers of meaning',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-ffab-4527-b0ca-0f364471f919',
                                    name: 'Target Audience',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Target Audience',
                                    description:
                                        'Designing your story for maximum connection',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fbdb-4cd1-b971-1a967afedc15',
                                    name: 'Desired Impact',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Desired Impact',
                                    description:
                                        'Designing your story to move and provoke readers',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0575-0097-4635-8612-1fcae71a4b12',
                                    name: 'Writing Influences',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Writing Influences',
                                    description:
                                        'Strengthening your creative roots',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fb89-4803-9394-28d12ae12cdb',
                                    name: 'Character Choices and Actions',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Character Choices and Actions',
                                    description:
                                        'Revealing character through decisive moments',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fc93-4fc8-b017-28d8fa110959',
                                    name: 'Plot Depth',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Plot Depth',
                                    description:
                                        'Crafting a plot that resonates with meaning',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-ff68-49fa-9e64-2aaa101f36b9',
                                    name: 'Subplot Setup',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Subplot Setup',
                                    description:
                                        'Adding depth and dimension with a complementary narrative',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fe81-4a45-931c-43c98a187f6f',
                                    name: 'Story World',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Story World',
                                    description:
                                        'Crafting imaginative realms readers will want to explore',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0575-006f-4b0d-88e9-4a025b5318e6',
                                    name: 'Writing Goals',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Writing Goals',
                                    description:
                                        'Setting a north star for your writing career',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-f8bb-447e-8ed9-4af1014af3a6',
                                    name: 'Appearance and Identity',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Appearance and Identity',
                                    description:
                                        'Bringing characters to life through vivid descriptions',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fde8-4350-ac18-4e37c6d02e2a',
                                    name: 'Sequence Setup',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Sequence Setup',
                                    description:
                                        'Setting the stage for a powerful story unit',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fd05-45ac-8350-583ef0f113f5',
                                    name: 'Psychological Profile',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Psychological Profile',
                                    description:
                                        'Delving into the minds of your characters',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fd53-4f12-8003-5867822a1337',
                                    name: 'Sequence Conflict',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Sequence Conflict',
                                    description:
                                        'Designing sequences that keep readers on the edge of their seats',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fcdd-4639-a7dc-5dd5cf29bfee',
                                    name: 'Plot Structure',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Plot Structure',
                                    description:
                                        'Building a blueprint for an unforgettable story',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fcb8-4e46-97d9-604e17091352',
                                    name: 'Plot Setup',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Plot Setup',
                                    description:
                                        'Launching your story with intrigue and momentum',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fbb3-485e-af66-6057eefc65c2',
                                    name: 'Characters',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Characters',
                                    description:
                                        'Building a dynamic network of key players',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fdc8-46ca-aac0-61dd69518aa4',
                                    name: 'Sequence Resonance',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Sequence Resonance',
                                    description:
                                        'Crafting sequences that echo and amplify meaning',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fe31-4885-a7c6-693a6bceafca',
                                    name: 'Story Structure',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Story Structure',
                                    description:
                                        'Building a blueprint for an unforgettable story',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fbff-4a95-bac7-6a03204a2273',
                                    name: 'Emotional Journey',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Emotional Journey',
                                    description:
                                        'Crafting character arcs that resonate and inspire',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fe58-4a43-a1c2-70950238b280',
                                    name: 'Story Trials',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Story Trials',
                                    description:
                                        'Putting your protagonist through their paces',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fda3-4aac-9cba-74ddaef12ebc',
                                    name: 'Sequence Impact',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Sequence Impact',
                                    description:
                                        'Ensuring each sequence leaves a lasting mark',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fb0a-4760-9fa3-76d9154a566c',
                                    name: 'Backstory and Motivations',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Backstory and Motivations',
                                    description:
                                        'Discovering the why behind the what of your characters',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fef5-4901-8a6c-788044b07538',
                                    name: 'Subplot Development',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Subplot Development',
                                    description:
                                        'Interweaving subplots that deepen the narrative',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0575-0002-42e5-b55e-791e56941907',
                                    name: 'Voice and Communication',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Voice and Communication',
                                    description:
                                        'Breathing life into characters through their voice',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0575-0027-46a0-bf91-8a7ad063fcff',
                                    name: 'Writer Identity',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Writer Identity',
                                    description:
                                        'Uncovering your core identity as a writer',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fc6d-4ae1-8fab-a2e15a2a1193',
                                    name: 'Plot Denounement',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Plot Denounement',
                                    description:
                                        'Giving your audience a satisfying sendoff',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fc23-4463-856c-a6b14b92e3e3',
                                    name: 'Key Moments',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Key Moments',
                                    description:
                                        "Capturing the scenes your story can't live without",
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-ffde-4984-abd4-a92930ba15f4',
                                    name: 'Theme Exploration',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Theme Exploration',
                                    description:
                                        'Crafting stories with thematic depth and clarity',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fb5e-4959-a622-ad5202ddac99',
                                    name: 'Central Conflict',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Central Conflict',
                                    description:
                                        'Fueling your story with a compelling clash of wills',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fb35-481a-aec9-b6d42e64c02d',
                                    name: 'Basic Profile',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Basic Profile',
                                    description:
                                        'Building a foundation for your characters',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fe0b-4380-87fa-b78d7278537b',
                                    name: 'Story Fundamentals',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Story Fundamentals',
                                    description:
                                        'Laying the groundwork for an engaging tale',
                                    progress: 0,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0574-fd2b-443f-a5e0-e5ef40c22431',
                                    name: 'Relationships and Purpose',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Relationships and Purpose',
                                    description:
                                        'Designing secondary characters that complement and challenge',
                                    progress: 90,
                                    completed_at: null,
                                },
                                {
                                    id: '9c2d0575-00bc-4e8e-aa0c-e8e1789bc9db',
                                    name: 'Writing Style',
                                    icon: '/images/achievements/backstory.png',
                                    title: 'Writing Style',
                                    description:
                                        'Developing a signature storytelling style',
                                    progress: 100,
                                    completed_at: '2021-09-01 12:00:00',
                                },
                            ],
                            data: {
                                name: 'John Doe',
                                current_projects: [
                                    'Story about the ocean',
                                    'Poem about the mountains',
                                ],
                                growth_areas: ['Writing', 'Poetry'],
                                origin_story:
                                    "I've always loved writing and poetry, and I'm excited to share my work with the world.",
                                storytelling_strengths: [
                                    'Descriptive language',
                                    'Emotional depth',
                                ],
                                unique_perspective:
                                    'I love to explore the beauty of nature and the human experience through my writing.',
                                writing_motivation:
                                    "I'm inspired by the beauty of the world around me and the stories that are waiting to be told.",
                                desired_impact:
                                    'I hope to inspire others to see the world in a new light and appreciate the beauty that surrounds us.',
                                formats: ['Poetry', 'Short stories'],
                                genres: ['Nature', 'Romance'],
                                themes: ['Love', 'Nature', 'Adventure'],
                                bio: "I'm a writer and a poet, I love to write about the beauty of nature and the human experience. I'm also a big fan of the outdoors and love to go hiking and camping whenever I can.",
                            },
                            onboarded: true,
                        },
                    },
                },
            },
            {
                name: 'Story mocks',
                mocks: {
                    'story conversation get': {
                        url: '/api/v1/conversation/stories',
                        method: 'GET',
                        response: {
                            identifier:
                                'story_9c2ce7f0-2d47-46da-b84d-3ceee57cf2f6',
                            question: {
                                type: 'text',
                                content: 'Mocked question',
                                title: 'Mocked title',
                                audio: '/audio/question.mp3',
                            },
                        },
                    },
                    'story conversation': {
                        url: '/api/v1/conversation/stories',
                        method: 'POST',
                        response: {
                            identifier:
                                'story_9c2ce7f0-2d47-46da-b84d-3ceee57cf2f6',
                            question: {
                                type: 'text',
                                content: 'Mocked question',
                                title: 'Mocked title',
                            },
                            data: {
                                extracted: {
                                    'key-characters': 'Italian plumber',
                                    'impact-on-plot-and-theme':
                                        'Fighting monsters in a sewerage',
                                    title: 'Untitled: The Sewerage Showdown',
                                    premise:
                                        'The main objective of Plumber, to find Princess and save her from monsters',
                                    'protagonists-overarching-goal':
                                        'To find Princess and save her from monsters',
                                    'primary-obstacle': 'Monsters',
                                    stakes: 'Failure to save the Princess',
                                },
                            },
                        },
                    },
                    'stories list': {
                        url: '/api/v1/stories',
                        method: 'GET',
                        response: [
                            {
                                id: 'test',
                                name: 'Test story',
                                image: {
                                    path: 'https://placekeanu.com/200/300',
                                },
                                description: 'This is a test story',
                                type: 'Movie',
                                genres: ['Action', 'Comedy'],
                            },
                            {
                                id: 'test',
                                name: 'Test story 2',
                                image: {
                                    path: 'https://placekeanu.com/400/600',
                                },
                                description: 'This is a test story 2',
                                type: 'Book',
                                genres: ['Fantasy', 'Adventure'],
                            },
                        ]
                    },
                    'story details': {
                        url: '/api/v1/stories/test',
                        method: 'GET',
                        response: mockStory
                    }
                },
            },
            {
                name: 'Sequence',
                mocks: {
                    'sequence details': {
                        url: '/api/v1/stories/test/sequences/test',
                        method: 'GET',
                        response: {
                            id: 'test',
                            name: 'Test sequence',
                            story_id: 'test',
                            description: 'This is a test sequence',
                            type: 'Action',
                            genres: ['Action', 'Comedy'],
                        },
                    },
                }
            }
        ] as Array<{
            name: string
            mocks: {
                [key: string]: {
                    url: string
                    method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
                    response: any
                }
            }
        }>

        const enabledMocks = ref<string[]>([])

        return {
            groups,
            enabledMocks,
            toggleMock(mockName: string) {
                console.log(mockName, enabledMocks.value)
                if (enabledMocks.value.includes(mockName)) {
                    console.log('here')
                    enabledMocks.value = enabledMocks.value.filter(
                        (name) => name !== mockName
                    )
                } else {
                    enabledMocks.value.push(mockName)
                }
            },
            isActive(method: string, url: string) {
                return enabledMocks.value.some((mockName) => {
                    const group = groups.find((group) =>
                        Object.keys(group.mocks).includes(mockName)
                    )
                    if (!group) return false
                    return (
                        group.mocks[mockName].url.toLowerCase() ===
                            url.toLowerCase() &&
                        group.mocks[mockName].method.toLowerCase() ===
                            method.toLowerCase()
                    )
                })
            },
            getMockResponse(method: string, url: string) {
                const group = groups.find((groups) =>
                    Object.values(groups.mocks).some(
                        (mock) => mock.url === url && mock.method === method
                    )
                )
                if (!group) return null
                return Object.values(group.mocks).find(
                    (mock) => mock.url === url && mock.method === method
                )?.response
            },
        }
    },
    {
        persist: true,
    }
)
