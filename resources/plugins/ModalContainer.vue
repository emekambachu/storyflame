<template>
    <teleport to="body">
        <v-modal
            v-for="(modal, i) in modals"
            :key="modal.id"
            :attrs="modal.props"
            :component="modal.component"
            :data-id="modal.id"
            :order="modals.filter((m) => m.open).length - i"
            @close="closeModal(modal.id)"
            @closing="modals[i].open = false"
        />
    </teleport>
</template>

<script lang="ts" setup>
import VModal from '@/plugins/VModal.vue'
import { inject, PropType } from 'vue'
import { ModalDefinition } from '@/plugins/modalPlugin'

const { close, modals } = inject('modal')

defineOptions({
    inheritAttrs: false,
})

const props = defineProps({
    modals: {
        type: Array as PropType<ModalDefinition[]>,
        required: true,
    },
})

function closeModal(id: string) {
    close(id)
}
</script>

<style scoped></style>
