<template>
    <page-navigation-layout
        fixed
        no-back-text
        transparent
    >
        <tab-layout
            :tabs="[
                {
                    title: 'Elements',
                    template: 'elements',
                },
                {
                    title: 'Develop',
                    template: 'develop',
                },
                {
                    title: 'Arc',
                    template: 'arc',
                },

                {
                    title: 'Details',
                    template: 'details',
                },
            ]"
            class="!gap-0"
            header-height="400"
            collapse-header-height="200"
            menu-btn-class="w-full text-base text-stone-500 bg-stone-100 px-3 py-2 rounded-lg border-none"
            menu-btn-selected-class="w-full text-base text-stone-50 bg-stone-800 rounded-lg px-3 py-2 border-none"
            menu-container-class="w-full flex gap-2"
            menu-wrapper-class="mx-auto max-w-full w-full overflow-x-auto py-4 px-3 bg-white"
        >
            <default-element-header
                :title="sequence.name"
                :detail="sequence.description"
                :genres="sequence.genres"
                :tags="['TV Show', 'MA-16']"
                background="https://picsum.photos/1920/1080"
                class="pb-5 pt-20"
            />
            <template #develop>
                <sequence-develop-tab :data="sequence" />
            </template>
            <template #arc>
                <sequence-arc-tab :data="sequence" />
            </template>
            <template #details>
                <sequence-details-tab :data="sequence" />
            </template>
            <template #elements>
                <sequence-elements-tab :data="sequence" />
            </template>
        </tab-layout>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import SequenceArcTab from '@/components/SequenceArcTab.vue'
import SequenceDevelopTab from '@/components/SequenceDevelopTab.vue'
import SequenceDetailsTab from '@/components/SequenceDetailsTab.vue'
import SequenceElementsTab from '@/components/SequenceElementsTab.vue'

import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import TabLayout from '@/components/TabLayout.vue'
import DefaultElementHeader from '@/components/headers/DefaultElementHeader.vue'
import { useQuery } from '@tanstack/vue-query'
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { getSequence } from '@/utils/endpoints'

const sequence = {
    name: 'Arrival of King Robert and the Royal Family',
    description:
        'Kickstart the main political plot by bringing the royal family to Winterfell, highlighting the close yet strained relationship between the Starks and the Baratheons.',
    progress:
        'Your characters and themes are looking great, but your story could use some world building development.',
    story: {
        name: 'Game of Thrones',
    },
    image: {
        path: 'https://picsum.photos/900',
    },
    genres: ['Fantasy', 'Drama', 'Adventure', 'Political Intrigue'],

    kickstart:
        'Kickstart the main political plot by bringing the royal family to Winterfell, highlighting the close yet strained relationship between the Starks and the Baratheons.',
    drives_scene:
        'King Robert drives the sequence by his actions and decisions, from the moment of his arrival to his insistence on visiting the crypts.',
    goals: [
        {
            part: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            priority: 1,
        },
        {
            part: 'Introduction',
            description:
                'Introduce King Robert and the royal family to the audience.',
            priority: 2,
        },
    ],
    conflict_sources: [
        'Political Obligations vs. Personal Desires: Ned Stark faces the tension between his duty to the king and his desire to stay in the North away from the politics of the capital.',
        'Past Relationships vs. Present Realities: The reminiscence of past friendships contrasts with current political necessities and personal changes.',
        'Expectations vs. Reality: The grandeur and behavior of the royal family may not meet the expectations of the Stark family, highlighting differences and brewing discontent.',
    ],
    arc_images: [
        {
            image: 'https://picsum.photos/900',
        },
        {
            image: 'https://picsum.photos/900',
        },
    ],
    moments: [
        {
            title: 'Tyrion bonds with Jon at the Wall',
            description:
                'In facing the harsh realities of life at the Wall, he begins to realize the importance of standing up for the marginalized.',
            dialogues: [
                {
                    speaker: 'Robert Baratheon',
                    quote: "You've got fat.",
                    response: {
                        speaker: 'Eddard Stark',
                        quote: "And you've got older.",
                    },
                },
                {
                    speaker: 'Robert Baratheon',
                    quote: "You've got fat.",
                    response: {
                        speaker: 'Eddard Stark',
                        quote: "And you've got older.",
                    },
                },
            ],
            visual: {
                title: 'Visual',
                description:
                    'The dark, foreboding wall dimly lit by the a few torches showcases how underpowered they are.',
            },
            emotional: {
                title: 'Emotional',
                description: 'Something about the emotional moment or impact.',
            },
        },
    ],
    progress_list: [
        {
            name: 'Characters',
            progress: 1,
            count: 20,
        },
        {
            name: 'Sequences',
            progress: 2,
            count: 20,
        },
        {
            name: 'Themes',
            progress: 3,
            count: 20,
        },
        {
            name: 'Appeal',
            progress: 4,
            count: 20,
        },
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

    characters: [
        {
            story: 'Game of Thrones',
            episode: 'Ep: sldnfjs sdlkflsdjk skfdn',
            role: 'Protagonist',
            title: 'Daenerys Targaryen',
            type: 'Helpless Sibling',
            description: 'Gain independence and reclaim her ancestral throne.',
        },
        {
            story: 'Game of Thrones',
            episode: 'Ep: sldnfjs sdlkflsdjk skfdn',
            role: 'Protagonist',
            title: 'Daenerys Targaryen',
            type: 'Helpless Sibling',
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
    discrepancies: [
        {
            story: 'Game of Thrones',
            type: 'Plot',
            title: 'Setting for the Climax',
            description:
                'We’re uncertain about how you want to handle the climax, is it in the future or in 1880’s Paris?',
        },
        {
            story: 'Game of Thrones',
            type: 'Plot',
            title: 'Setting for the Climax',
            description:
                'We’re uncertain about how you want to handle the climax, is it in the future or in 1880’s Paris?',
        },
    ],
    achievements_not_started: [
        {
            icon: {
                path: 'https://picsum.photos/90',
            },
            type: 'Character development',
            time: '5 min',
            percent: 0,
            color: '#A516AD',

            story: 'Game of thrones',
            subtitle: 'Meeting the Wizard',

            title: 'Unexpected Decisions',
            description:
                'You established an unexpected twist that endears people towards Tyrion.',
        },
    ],
    achievements_in_progress: [
        {
            icon: {
                path: 'https://picsum.photos/90',
            },
            type: 'Character development',
            time: '5 min',
            percent: 20,
            color: '#A516AD',

            story: 'Game of thrones',
            subtitle: 'Meeting the Wizard',

            title: 'Unexpected Decisions',
            description:
                'You established an unexpected twist that endears people towards Tyrion.',
        },
    ],
    achievements_completed: [
        {
            icon: {
                path: 'https://picsum.photos/900',
            },
            date: '25 Apr',
            percent: 20,
            color: '#099AB1',

            story: 'Game of thrones',
            subtitle: 'Meeting the Wizard',

            title: 'Unexpected Decisions',
            description:
                'You established an unexpected twist that endears people towards Tyrion.',
        },
    ],

    plot: {
        title: 'Plot',
        readiness: 4,
        achievements: [
            { image: { path: 'https://picsum.photos/900' } },
            { image: { path: 'https://picsum.photos/900' } },
        ],
        amount_max_achievements: 12,

        effectiveness:
            'The plot sets up conflicts and power dynamics that have the potential to test the characters` morality, but the stakes and central tensions need to be heightened to fully realize the writer`s vision of exploring the cost of power in a gritty world.',
        audience:
            'The morally grey characters and their high-stakes conflicts should appeal to the target audience of mature fantasy drama fans. However, the characters need to be quickly established as compelling and relatable to fully engage viewers. Strengthening the sense of character perspective and ensuring each one has a clear goal will help audiences invest in their journeys.',
        market_comparisons:
            'Prospera`s characters have the complex moral shading and mix of noble and selfish motivations that define prestige fantasy drama protagonists. However, their distinct personalities and voices need to be sharpened to match the memorable characterization of market comp ensembles. The characters need to command the screen and leave the audience eager to follow them.',
        potential_explorations: [
            {
                icon: {
                    path: 'https://picsum.photos/90',
                },
                type: 'Character development',
                time: '5 min',
                percent: 20,
                color: '#A516AD',

                story: 'Game of thrones',
                subtitle: 'Meeting the Wizard',

                title: 'Unexpected Decisions',
                description:
                    'You established an unexpected twist that endears people towards Tyrion.',
            },
        ],
        something_new: [
            {
                icon: {
                    path: 'https://picsum.photos/90',
                },
                type: 'Character development',
                time: '5 min',
                percent: 20,
                color: '#A516AD',

                story: 'Game of thrones',
                subtitle: 'Meeting the Wizard',

                title: 'Unexpected Decisions',
                description:
                    'You established an unexpected twist that endears people towards Tyrion.',
            },
        ],
    },

    characters_list: {
        title: 'Characters',
        readiness: 2,
        achievements: [
            { image: { path: 'https://picsum.photos/900' } },
            { image: { path: 'https://picsum.photos/900' } },
        ],
        amount_max_achievements: 12,

        effectiveness:
            'The plot sets up conflicts and power dynamics that have the potential to test the characters` morality, but the stakes and central tensions need to be heightened to fully realize the writer`s vision of exploring the cost of power in a gritty world.',
        audience:
            'The morally grey characters and their high-stakes conflicts should appeal to the target audience of mature fantasy drama fans. However, the characters need to be quickly established as compelling and relatable to fully engage viewers. Strengthening the sense of character perspective and ensuring each one has a clear goal will help audiences invest in their journeys.',
        market_comparisons:
            'Prospera`s characters have the complex moral shading and mix of noble and selfish motivations that define prestige fantasy drama protagonists. However, their distinct personalities and voices need to be sharpened to match the memorable characterization of market comp ensembles. The characters need to command the screen and leave the audience eager to follow them.',
        potential_explorations: [
            {
                icon: {
                    path: 'https://picsum.photos/90',
                },
                type: 'Character development',
                time: '5 min',
                percent: 20,
                color: '#A516AD',

                story: 'Game of thrones',
                subtitle: 'Meeting the Wizard',

                title: 'Unexpected Decisions',
                description:
                    'You established an unexpected twist that endears people towards Tyrion.',
            },
        ],
        something_new: [
            {
                icon: {
                    path: 'https://picsum.photos/90',
                },
                type: 'Character development',
                time: '5 min',
                percent: 20,
                color: '#A516AD',

                story: 'Game of thrones',
                subtitle: 'Meeting the Wizard',

                title: 'Unexpected Decisions',
                description:
                    'You established an unexpected twist that endears people towards Tyrion.',
            },
        ],
    },

    sequences: {
        title: 'Sequences',
        readiness: 1,
        achievements: [
            { image: { path: 'https://picsum.photos/900' } },
            { image: { path: 'https://picsum.photos/900' } },
        ],
        amount_max_achievements: 12,

        effectiveness:
            'The plot sets up conflicts and power dynamics that have the potential to test the characters` morality, but the stakes and central tensions need to be heightened to fully realize the writer`s vision of exploring the cost of power in a gritty world.',
        audience:
            'The morally grey characters and their high-stakes conflicts should appeal to the target audience of mature fantasy drama fans. However, the characters need to be quickly established as compelling and relatable to fully engage viewers. Strengthening the sense of character perspective and ensuring each one has a clear goal will help audiences invest in their journeys.',
        market_comparisons:
            'Prospera`s characters have the complex moral shading and mix of noble and selfish motivations that define prestige fantasy drama protagonists. However, their distinct personalities and voices need to be sharpened to match the memorable characterization of market comp ensembles. The characters need to command the screen and leave the audience eager to follow them.',
        potential_explorations: [
            {
                icon: {
                    path: 'https://picsum.photos/90',
                },
                type: 'Character development',
                time: '5 min',
                percent: 20,
                color: '#A516AD',

                story: 'Game of thrones',
                subtitle: 'Meeting the Wizard',

                title: 'Unexpected Decisions',
                description:
                    'You established an unexpected twist that endears people towards Tyrion.',
            },
        ],
        something_new: [
            {
                icon: {
                    path: 'https://picsum.photos/90',
                },
                type: 'Character development',
                time: '5 min',
                percent: 20,
                color: '#A516AD',

                story: 'Game of thrones',
                subtitle: 'Meeting the Wizard',

                title: 'Unexpected Decisions',
                description:
                    'You established an unexpected twist that endears people towards Tyrion.',
            },
        ],
    },
    story_info: [
        {
            title: 'Setup',
            description:
                'The episode establishes the status quo of the Stark family in Winterfell, introducing Ned Stark as an honorable and dutiful lord, his wife Catelyn, and their children. It also sets up the political landscape of Westeros and the looming threat of the White Walkers beyond the Wall.',
        },
        {
            title: 'Inciting Incident',
            description:
                'King Robert Baratheon arrives at Winterfell and asks Ned to serve as the new Hand of the King, following the suspicious death of the previous Hand, Jon Arryn. This event disrupts Ned`s life and sets the main plot in motion, forcing him to leave his family and navigate the dangerous political waters of King`s Landing.',
        },
        {
            title: 'Trials and Complications',
            description:
                'In the pilot episode, the trials and complications are primarily established through exposition and foreshadowing. Ned`s reluctance to accept the position, the tensions between the Starks and Lannisters, and the introduction of the exiled Targaryens across the Narrow Sea all hint at the challenges and obstacles to come.',
        },
        {
            title: 'The Midpoint Twist',
            description:
                'As this is a pilot episode, there is no clear midpoint twist. However, the episode does end with a shocking moment - Bran Stark`s fall from the tower after witnessing Jaime and Cersei Lannister`s incestuous relationship. This event sets up a major plot point and source of conflict for future episodes.',
        },
        {
            title: 'The Crisis Point',
            description:
                'The pilot episode does not reach a clear crisis point for the protagonist, as it primarily focuses on setting up the characters and their world. However, Bran`s fall and the implied consequences for the Stark family could be seen as a mini-crisis point that will lead to further complications.',
        },
        {
            title: 'The Climax',
            description:
                'The episode does not have a traditional climax, as it is setting the stage for the larger story to come. The closest thing to a climax in the pilot is the shocking final scene with Bran`s fall, which leaves the audience with a sense of suspense and uncertainty.',
        },
        {
            title: 'The Resolution',
            description:
                'The episode does not have a traditional climax, as it is setting the stage for the larger story to come. The closest thing to a climax in the pilot is the shocking final scene with Bran`s fall, which leaves the audience with a sense of suspense and uncertainty.',
        },
        {
            title: 'The Hook',
            description:
                'The pilot episode of Game of Thrones presents several hooks to engage the audience and encourage them to continue watching the series:',
            list: [
                'The complex political intrigue and power struggles hinted at throughout the episode',
                'The looming supernatural threat of the White Walkers beyond the Wall',
                'The shocking final scene with Bran`s fall and the revelation of Jaime and Cersei`s secret relationship',
                'The introduction of compelling characters with rich backstories and motivations, such as Ned Stark, Daenerys Targaryen, and Tyrion Lannister',
            ],
        },
    ],

    arc_paragraphs: [
        '  King Robert Baratheon arrives at Winterfell with his royal entourage, setting a grand and ceremonial tone. The Stark family, led by Eddard &quotNed&quot Stark, prepares to receive their long-time friend and his family. As Robert dismounts, he jovially mocks Ned by exclaiming, &quotYou`ve got fat,&quot to which Ned retorts about Robert`s aging, highlighting their camaraderie. This initial interaction encapsulates the deep bond and mutual respect between the two men, despite the years and their differing paths.',
        'The scene transitions to the crypts of Winterfell, where Robert and Ned pay their respects to Lyanna Stark, Ned’s deceased sister and Robert`s former betrothed. The atmosphere in the crypts is somber and reflective, underscoring the weight of the past on their present lives. Robert, who still mourns Lyanna, implores Ned to come south to King`s Landing and serve as the Hand of the King, a role recently vacated by the suspicious death of Jon Arryn. Robert suggests uniting their families through marriage, indicating his desire to solidify their alliance in both personal and political realms. This conversation sets the stage for Ned’s eventual journey south and the unfolding political intrigue.',
    ],

    details_goals: [
        {
            title: 'Friendship',
            description:
                'Establish the longstanding friendship between King Robert and Eddard Stark.',
        },
        {
            title: 'Political',
            description:
                'Set the political tone and hint at the central conflicts of the series.',
        },
        {
            title: 'Relationship',
            description:
                'Showcase the dynamics and tensions within both the royal family and the Stark family.',
        },
    ],
    details_obtacles: [
        {
            title: 'Political Obligations vs. Personal Desires',
            description:
                'Ned Stark faces the tension between his duty to the king and his desire to stay in the North away from the politics of the capital.',
        },
        {
            title: 'Past Relationships vs. Present Realities',
            description:
                'The reminiscence of past friendships contrasts with current political necessities and personal changes.',
        },
        {
            title: 'Expectations vs. Reality',
            description:
                'The grandeur and behavior of the royal family may not meet the expectations of the Stark family, highlighting differences and brewing discontent.',
        },
    ],
    details_plot:
        'The arrival sets in motion the main storyline of the series, including Ned’s later move to the capital, which brings his family into the heart of the conflict.',
    details_characters: [
        {
            title: 'Robert Baratheon',
            description:
                'Revealed as a powerful but weary king who still clings to the memories of a past war and lost love.',
        },
        {
            title: 'Ned Stark',
            description:
                'Shown as a loyal friend and dutiful lord, torn between his peaceful life in Winterfell and the demands of his friendship with the king.',
        },
        {
            title: 'Cersei Lannister',
            description:
                'Introduced subtly but effectively as a queen with her own agendas, observing and reacting with calculated restraint.',
        },
    ],
    details_audience_learns: [
        {
            title: 'Connection through Lyanna',
            description:
                'Revealed as a powerful but weary king who still clings to the memories of a past war and lost love.',
        },
        {
            title: 'Political Landscape of the Seven Kingdoms',
            description:
                'Insight is provided into the political landscape of the Seven Kingdoms, including the weight of the crown on Robert and the looming tensions.',
        },
        {
            title: 'Key Motivations and Personalities',
            description:
                'The distinct personalities and motivations of key characters are highlighted, setting up their roles and conflicts for the series.',
        },
    ],
}

const route = useRoute()
// const storyId = computed(() => route.params.story)
// const sequenceId = computed(() => route.params.sequence)

// const { data: sequence } = useQuery({
//     queryKey: ['sequence', sequenceId.value],
//     queryFn: () => getSequence(storyId.value, sequenceId.value),
//     select(data) {
//         return data.data
//     },
// })
</script>

<style scoped></style>
