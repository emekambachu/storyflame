<template>
    <page-navigation-layout
        class="text-slate-400"
        fixed
        no-back-text
        transparent
    >
        <div class="flex flex-col gap-4 pb-8">
            <!-- 
                    { title: 'Overview', template: 'overview' },
             -->
            <tab-layout
                :tabs="[
                    { title: 'Develop', template: 'develop' },
                    { title: 'Details', template: 'details' },
                    { title: 'Arcs', template: 'arcs' },
                    { title: 'Relational', template: 'relational' },
                    { title: 'Backstory', template: 'backstory' },
                    { title: 'Speech', template: 'speech' },
                    { title: 'References', template: 'references' },
                ]"
                class="!gap-0"
                header-height="260"
                collapse-header-height="200"
            >
                <header-animated
                    collapse-header-height="68"
                    header-height="260"
                >
                    <character-header
                        :character="character"
                        class="py-5"
                    />
                    <template #sticky>
                        <tab-layout-tabs
                            menu-btn-class="w-full text-base text-stone-500 bg-stone-100 px-3 py-2 rounded-lg border-none"
                            menu-btn-selected-class="w-full text-base text-stone-50 bg-stone-800 rounded-lg px-3 py-2 border-none"
                            menu-container-class="w-full flex gap-2"
                            menu-wrapper-class="mx-auto max-w-full w-full overflow-x-auto py-4 px-3 bg-white"
                        />
                    </template>
                </header-animated>

                <template #overview>
                    <character-overview-tab :data="character" />
                </template>
                <template #develop>
                    <develop-tab :data="character" />
                </template>
                <template #details>
                    <character-details-tab :data="character" />
                </template>
                <template #arcs>
                    <character-arcs-tab :data="character" />
                </template>
                <template #relational>
                    <character-relational-tab :data="character" />
                </template>
                <template #backstory>
                    <character-backstory-tab :data="character" />
                </template>
                <template #speech>
                    <character-speech-tab :data="character" />
                </template>
                <template #references>
                    <character-references-tab :data="character" />
                </template>
            </tab-layout>
        </div>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import FlameIcon from '@/components/FlameInProgressCircle.vue'
import PointIcon from '@/components/icons/PointIcon.vue'

import TabLayout from '@/components/TabLayout.vue'
import AchievementsTab from '@/components/AchievementsTab.vue'
import CharacterOverviewTab from '@/components/CharacterOverviewTab.vue'
import DevelopTab from '@/components/DevelopTab.vue'
import CharacterDetailsTab from '@/components/CharacterDetailsTab.vue'
import CharacterArcsTab from '@/components/CharacterArcsTab.vue'
import CharacterRelationalTab from '@/components/CharacterRelationalTab.vue'
import CharacterBackstoryTab from '@/components/CharacterBackstoryTab.vue'
import CharacterSpeechTab from '@/components/CharacterSpeechTab.vue'
import CharacterReferencesTab from '@/components/CharacterReferencesTab.vue'

import PageNavigationLayout from '@/components/PageNavigationLayout.vue'
import HeaderAnimated from '@/components/ui/HeaderAnimated.vue'
import TabLayoutTabs from '@/components/ui/TabLayoutTabs.vue'
import CharacterHeader from '@/components/headers/CharacterHeader.vue'

const character = {
    name: 'Tyrion Lannister',
    readiness: 60,
    description:
        'Has a deep-seated desire for love and acceptance, despite his cynical exterior',

    role: 'Protagonist',
    quote: 'I have a tender spot in my heart for cripples, bastards, and broken things',

    features: ['The Patriarch', 'Noble Top Enforcer'],

    progress_description:
        'Your characters and themes are looking great, but your story could use some world building development.',

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

    images: [
        { image: { path: 'https://picsum.photos/900' } },
        { image: { path: 'https://picsum.photos/900' } },
    ],
    goals: [
        'Be respected for his intellect',
        'Find his place in the world',
        'Protect his family',
    ],
    character_details: [
        {
            section_title: 'Demographics',
            data: [
                {
                    title: 'Name',
                    value: 'Tyrion Lannister',
                },
                {
                    title: 'Aliases',
                    value: 'The Little Lion, The Imp, Lord, Lord Tyrion',
                },
                {
                    title: 'Age',
                    value: '32 (pilot episode)',
                },
                {
                    title: 'Ethnicity',
                    value: 'Westerosi',
                },
                {
                    title: 'Race',
                    value: 'Human',
                },
                {
                    title: 'Occupation',
                    value: 'Member of the wealthy and powerful Lannister family',
                },
            ],
        },
        {
            section_title: 'Appearance',
            data: [
                {
                    title: 'Height',
                    value: 'Dwarf',
                },
                {
                    title: 'Hair Color',
                    value: 'Blonde',
                },
                {
                    title: 'Eye Color',
                    value: 'Mismatched (green and black)',
                },
                {
                    title: 'Distinguishing Features:',
                    value: 'Pronounced forehead, mismatched eyes',
                },
            ],
        },
        {
            section_title: 'Introduction to the Audience',
            description:
                'Introduced as a clever, sarcastic, and pleasure-seeking character',
        },
    ],
    personality: {
        strong_sides: ['Highly intelligent', 'High Conscientiousness'],
        week_sides: ['Low Extraversion', 'Low Neuroticism'],

        list: [
            {
                title: 'Strengths',
                description: 'Intelligent, witty, strategic, adaptable',
            },
            {
                title: 'Weaknesses',
                description:
                    'Cynical, self-loathing, struggles with family relationships',
            },
            {
                title: 'Core Motivations',
                description:
                    'Tyrion is driven by a deep desire for knowledge, recognition, and acceptance in a world that often marginalizes him due to his physical appearance. He seeks to prove his worth, protect his family, and navigate complex political landscapes while maintaining his intellectual and emotional independence.',
            },
            {
                title: 'Decision-Making Process',
                list: [
                    'Tyrion relies heavily on his logical analysis, strategic thinking, and adaptability when making decisions.',
                    'He excels at assessing complex situations, anticipating challenges, and generating creative solutions.',
                    'Prioritizes self-preservation',
                ],
            },
            {
                title: 'Growth Potential',
                list: [
                    'Tyrion has significant room for growth in the areas of emotional vulnerability, self-acceptance, and assertiveness.',
                    'He has the potential to learn to open up and form deeper connections with others, embrace his unique qualities beyond societal expectations, and stand up for himself and his beliefs more directly.',
                ],
            },
            {
                title: 'Emotional Landscape',
                list: [
                    'Despite his logical nature, Tyrion has a rich emotional world that he often keeps hidden behind a façade of wit and sarcasm.',
                    'He has a deep capacity for empathy, as seen in his compassion for the marginalized and his ability to understand others` emotions.',
                ],
            },
        ],
    },

    arcs: {
        episod_arc: [
            {
                title: 'Setup',
                description:
                    'Tyrion is introduced as a witty, intelligent, and hedonistic member of the powerful Lannister family.',
            },
            {
                title: 'Inciting Incident',
                description:
                    'Tyrion decides to accompany Jon Snow to the Wall, setting in motion a series of events that will challenge his worldview and loyalties.',
            },
            {
                title: 'Trials and Complications',
                description:
                    'Tyrion faces the harsh realities of life at the Wall and begins to empathize with the Night`s Watch and the marginalized.',
            },
        ],
        episod_impact: [
            {
                title: 'In Relation to the Series Arc',
                description:
                    'The pilot episode serves as the setup for Tyrion`s character, establishing his initial state, desires, fears, strengths, and weaknesses. It also sets in motion his character arc, as his decision to accompany Jon Snow to the Wall exposes him to new perspectives and challenges that will shape his growth throughout the series.',
            },
        ],
        moments: [
            {
                headerTitle: 'Favorite Moment',
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
                    description:
                        'Something about the emotional moment or impact.',
                },
            },
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
                    description:
                        'Something about the emotional moment or impact.',
                },
            },
        ],
    },
    relational: {
        episode_relationships: [
            {
                sectionTitle: 'This Episode',
                data: [
                    {
                        title: 'Friends & Allies',
                        value: 'Develops a friendship with Jon Snow',
                    },
                    {
                        title: 'Enemies',
                        value: 'Sets up future antagonistic relationships with the Starks',
                    },
                ],
            },
        ],
        series_relationships: [
            {
                sectionTitle: 'This Episode',
                data: [
                    {
                        title: 'Father',
                        value: 'Tywin Lannister (strained relationship)',
                    },
                    {
                        title: 'Siblings',
                        value: 'Jaime Lannister (close but complex), Cersei Lannister (antagonistic)',
                    },
                ],
            },
        ],
        social_impact: [
            {
                title: 'Perceptions by Others',
                list: [
                    'Viewed as an outsider and often underestimated due to his dwarfism',
                    'Respected for his intellect but resented for his sharp tongue and Lannister name',
                ],
            },
            {
                title: 'Appeals to Character Types',
                description:
                    'Those valuing peace, empathy, and moral leadership; individuals seeking a compassionate and understanding leader.',
            },
            {
                title: 'Repels Character Types',
                description:
                    'Those who prioritize power, control, or immediate results over ethical considerations and long-term peace.',
            },
            {
                title: 'Relationships and Attachments',
                list: [
                    'Tyrion has a complicated relationship with his family, craving their acceptance and approval while also resenting their mistreatment and underestimation of him.',
                    'He forms close bonds with those who can see beyond his physical appearance and appreciate his intellect and humor, such as his brother Jaime and his friend Bronn.',
                ],
            },
            {
                title: 'Helping Others in Trouble',
                subthemes: [
                    {
                        title: 'Friends/Family',
                        description:
                            'Prioritize their well-being while trying to find solutions that align with his values.',
                    },
                    {
                        title: 'Those in their community',
                        description:
                            'Engage in dialogue, seeking consensus and understanding to navigate challenges collectively.',
                    },
                    {
                        title: 'Those outside their community',
                        description:
                            'Attempt to understand their motives and find common ground, even in adversity.',
                    },
                ],
            },
        ],
    },
    backstory: {
        sections: [
            {
                title: 'Early Life',
                description:
                    'Tyrion Lannister was born as the youngest son of Tywin Lannister, Lord of Casterly Rock and head of House Lannister. As a dwarf, Tyrion faced prejudice and ridicule from an early age, including from his own family, particularly his father and sister. Despite this, his brother Jaime always treated him with love and respect.',
            },
            {
                title: 'Family Dynamics',
                description:
                    "Tyrion had a complex relationship with his family. His mother died giving birth to him, causing his father to resent him. Tywin openly despised Tyrion for his dwarfism and the death of his wife. Cersei, Tyrion's sister, also treated him with contempt. However, Tyrion shared a close bond with his brother Jaime, who was always protective and supportive of him.",
            },
            {
                title: 'Education and Training',
                description:
                    "Despite his physical limitations, Tyrion possessed a sharp intellect and a love for knowledge. He read voraciously from a young age, giving him a well-rounded education and a quick wit. Tyrion's intelligence and cunning became his greatest assets, compensating for his lack of physical prowess.",
            },
            {
                title: 'Traumatic Events',
                description:
                    "One of the most defining moments in Tyrion's life was his brief marriage to Tysha, a peasant girl. After learning that the marriage was a farce orchestrated by Jaime, Tywin forced Tyrion to watch as his guards raped Tysha and made Tyrion participate. This traumatic experience left deep emotional scars and shaped Tyrion's views on love and trust.",
            },
            {
                title: 'Reputation in Westeros',
                description:
                    'Throughout his life, Tyrion built a reputation as a drinker, a whoremonger, and "the Imp" - a derogatory nickname referring to his dwarfism. Many saw him as a lecherous and untrustworthy figure. However, those who got to know him discovered his intelligence, wit, and political acumen. His reputation as a clever and ruthless player in the game of thrones grew over time.',
            },
            {
                title: 'Key Early Life Accomplishments',
                description:
                    'Despite the challenges he faced growing up, Tyrion achieved several notable accomplishments before the main events of the series:',
                list: [
                    'He became a voracious reader and developed a sharp intellect, which he used to compensate for his physical limitations.',
                    'Tyrion traveled to Essos and beyond, gaining worldly experience and knowledge that set him apart from his peers in Westeros.',
                    'He formed a close bond with his brother Jaime, which would prove to be one of his most enduring and influential relationships.',
                    'Tyrion learned to use his wit and words as weapons to navigate the treacherous social and political landscape of Westeros.',
                ],
            },
        ],
    },
    speech: {
        sections: [
            {
                title: 'Wit and Sarcasm',
                description:
                    "Tyrion's speech is characterized by his sharp wit, sarcasm, and self-deprecating humor. He often used his intellect and words as weapons to navigate the treacherous political landscape of Westeros. His clever quips and biting remarks became his trademark, disarming his opponents and endearing him to the audience.",
                quote: 'Never forget what you are, the rest of the world will not. Wear it like armor and it can never be used to hurt you.',
            },
            {
                title: 'Persuasion and Manipulation',
                description:
                    'As a skilled orator, Tyrion was adept at using his words to persuade, manipulate, and outmaneuver others. He could talk his way out of difficult situations and turn enemies into allies with his silver tongue. His ability to read people and appeal to their desires made him a formidable negotiator.',
                quote: "It's easy to confuse 'what is' with 'what ought to be,' especially when 'what is' has worked out in your favor.",
            },
            {
                title: 'Self-Reflection and Introspection',
                description:
                    "Despite his outward bravado, Tyrion's speech often revealed moments of deep introspection and self-reflection. He grappled with his identity as a Lannister and a dwarf, questioning his place in the world. Through his words, we see a man struggling to reconcile his desires, his family's expectations, and his own moral compass.",
                quote: 'I have a tender spot in my heart for cripples, bastards, and broken things.',
            },
            {
                title: 'Empathy and Understanding',
                description:
                    'In his interactions with other marginalized characters, like Jon Snow or Bran Stark, Tyrion displayed empathy and understanding. Having faced discrimination himself, he could relate to their experiences and offer counsel and support. His words often conveyed a sense of shared struggle and a desire for acceptance.',
                quote: 'I have a soft spot for bastards, cripples, and broken things.',
            },
            {
                title: 'Drunken Philosophizing',
                description:
                    "Some of Tyrion's most profound and memorable lines were delivered while he was deep in his cups. Under the influence of wine, he would often wax poetic about life, love, and the cruel nature of the world. These moments of drunken philosophizing revealed the depth of his character and his grappling with existential questions.",
                quote: 'I drink and I know things.',
            },
        ],
    },
    references: {
        archetypes: [
            {
                title: 'The Trickster',
                description:
                    'A classic archetype in literature, known for their clever schemes, witty banter, and ability to subvert expectations.',
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            'Relies on intelligence and wit to overcome obstacles',
                            'Subverts societal norms and expectations.',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Tyrion's physical limitations set him apart from typical trickster figures",
                            'His actions are often motivated by a desire for acceptance and respect',
                        ],
                    },
                ],
            },
            {
                title: 'The Underdog',
                description:
                    'A character who faces adversity and must work harder than others to prove their worth, often garnering sympathy from the audience.',
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            'Faces constant adversity and discrimination',
                            'Must work harder than others to prove his worth',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Tyrion's highborn status grants him certain privileges and resources",
                            'His intellect and cunning set him apart from typical underdog figures',
                        ],
                    },
                ],
            },
        ],
        historical_figures: [
            {
                title: 'Niccolò Machiavelli',
                description:
                    'An Italian diplomat, writer, and philosopher known for his treatise on realist political theory, "The Prince."',
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            'Skilled in political maneuvering and strategy',
                            'Advocates for pragmatism and realpolitik',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Tyrion's story arc includes elements of personal growth, redemption, and the search for acceptance, which are not central themes in Machiavelli's philosophy",
                        ],
                    },
                ],
            },
            {
                title: 'Winston Churchill',
                description:
                    'A British statesman, orator, and Prime Minister known for his leadership during World War II and his witty remarks.',
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            'Renowned for their sharp wit and clever turns of phrase',
                            'Able to inspire and lead others through difficult',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Tyrion's moral ambiguity, personal vices, and complex family dynamics set him apart from Churchill's more straightforward public persona and leadership role.",
                        ],
                    },
                ],
            },
        ],
        fictional_references: [
            {
                title: 'Richard III (Shakespeare)',
                description:
                    "The titular character of Shakespeare's historical play, a deformed and manipulative figure who schemes his way to the English throne.",
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            'Physically deformed and underestimated by others',
                            'Skilled in political manipulation and strategy',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            'Richard III is a villainous figure, while Tyrion is more of an antihero',
                            "Tyrion's wit and humor set him apart from Richard's darker nature",
                        ],
                    },
                ],
            },
            {
                title: 'Bilbo/Frodo Baggins (Tolkien)',
                description:
                    'The main protagonists of J.R.R. Tolkien\'s "The Hobbit" and "The Lord of the Rings," respectively. They are small, unassuming hobbits who play crucial roles in shaping the fate of Middle-earth.',
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            'Underestimated by others due to their size and appearance',
                            'Play crucial roles in shaping the fate of their respective worlds.',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            'Tyrion is more morally ambiguous than the virtuous hobbits',
                            "Tyrion's wit and sarcasm set him apart from the more earnest Baggins characters",
                        ],
                    },
                ],
            },
            {
                title: 'Gandalf (Tolkien)',
                description:
                    "A wise and powerful wizard in J.R.R. Tolkien's Middle-earth legendarium, serving as a mentor and guide to the main characters.",
                lists: [
                    {
                        listTitle: 'Similarities',
                        list: [
                            'Known for their wisdom, intellect, and strategic thinking',
                            'Serve as mentors and guides to other characters',
                        ],
                    },
                    {
                        listTitle: 'Differences',
                        list: [
                            "Tyrion's moral ambiguity and personal struggles differ from Gandalf's more archetypal sage character",
                        ],
                    },
                ],
            },
        ],
    },
}
</script>

<style scoped></style>
