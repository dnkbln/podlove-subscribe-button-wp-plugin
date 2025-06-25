<template>
    <podlove-button variant="secondary" size="small" @click="openAddButton()">Add new</podlove-button>
    <Modal size="medium" :open="modalOpen" @close="closeAddButton()">
        <ButtonEdit :button="button"></ButtonEdit>
    </Modal>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { injectStore, mapState } from 'redux-vuex';

import { SubscribeButton } from '../../../types/buttons.types';
import Modal from '../../../components/modal/Modal.vue';
import PodloveButton from '../../../components/button/Button.vue'
import ButtonEdit from './ButtonEdit.vue';
import { add as addButton } from '../../../store/buttons.store';
import { selectors } from '../../../store';

const store = injectStore();

const state = mapState({
  buttons: selectors.buttons.buttons,
  lastCreatedId: selectors.buttons.lastCreatedId
});

const modalOpen = ref(false);

const button = computed(() => {
  return state.buttons.find((b: SubscribeButton) => b.id === state.lastCreatedId.value) || null;
});

function openAddButton() {
    store.dispatch(addButton())
    modalOpen.value = true
}

function closeAddButton() {
    modalOpen.value = false
}
</script>
