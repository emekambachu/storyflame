<template>
    <teleport to="body">
        <v-modal
            v-for="(modal, i) in modals"
            :key="modal.id"
            :attrs="modal.props"
            :component="modal.component"
            :data-id="modal.id"
            :open="modal.open"
            :order="modals.filter((m) => m.open).length - i"
            v-bind="{
                ...modal.props,
                ...createEventCallbacks(modal.id, modal.events),
            }"
            @close="closeModal(modal.id, true)"
        />
    </teleport>
</template>

<script lang="ts" setup>
import VModal from '@/plugins/VModal.vue'
import { inject } from 'vue'
import { ModalDefinition, ModalEventCallback, modalInjectKey } from '@/plugins/modalPlugin'

const { modals, close } = inject(modalInjectKey)!!

defineOptions({
    inheritAttrs: false,
})

function closeModal(id: string, force: boolean = false) {
    close(id, force)
}

function createEventCallbacks(
    modal: ModalDefinition,
    events: Record<string, ModalEventCallback>
) {
    return Object.entries(events).reduce(
        (acc, [event, callback]) => {
            acc[event] = (...payload: any[]) => {
                callback(
                    payload,
                    (force: boolean = false) => closeModal(modal.id, force),
                    modal
                )
            }
            return acc
        },
        {} as Record<string, (...payload: any[]) => void>
    )
}
</script>

<style scoped></style>
