<template>
    <page-navigation-layout>
        <conversation-engine
            endpoint="/api/v1/conversation/stories"
            @extract="onExtract"
            @finish="onFinish"
        >
            <template #header>
                <header class="flex w-full items-start justify-between gap-2">
                    <div
                        class="h-11 w-11 shrink-0 rounded-lg bg-gray-400"
                    ></div>
                    <!--                    <div-->
                    <!--                        class="grow line-clamp-1 text-start font-fjalla text-xl"-->
                    <!--                    >-->
                    <!--                        Lorem ipsum dolor sit amet, consectetur adipisicing-->
                    <!--                        elit. Autem cum cupiditate dicta eaque earum eligendi,-->
                    <!--                        eum, illum ipsa laudantium molestiae molestias, numquam-->
                    <!--                        pariatur possimus quasi qui quidem repellat repudiandae-->
                    <!--                        tenetur?-->
                    <!--                    </div>-->
                    <staggered-text-animation
                        v-memo="newStoryTitle"
                        :texts="[
                            {
                                modelValue: newStoryTitle,
                                class: 'line-clamp-1 whitespace-nowrap grow text-start font-fjalla text-xl',
                                is: 'h3',
                            },
                        ]"
                    />
                    <v-button class="shrink-0">Finish</v-button>
                </header>
            </template>
        </conversation-engine>
    </page-navigation-layout>
</template>

<script lang="ts" setup>
import PageProfileLayout from '@/components/PageNavigationLayout.vue'
import ConversationEngine from '@/views/ConversationEngine.vue'
import VButton from '@/components/VButton.vue'
import { ref } from 'vue'
import StaggeredTextAnimation from '@/components/StaggeredTextAnimation.vue'
import PageNavigationLayout from '@/components/PageNavigationLayout.vue'

const newStoryTitle = ref('New Story')

function onFinish() {
    console.log('finish')
}

function onExtract(data: any) {
    if (data.title) {
        newStoryTitle.value = data.title
    }
}
</script>

<style scoped></style>
