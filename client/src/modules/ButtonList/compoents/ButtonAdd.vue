<template>
    <podlove-button variant="secondary" size="small" @click="openAddButton()">Add new</podlove-button>
    <Modal size="medium" :open="modalOpen" @close="closeAddButton()">
        <ButtonEdit v-if="button" :button="button"></ButtonEdit>
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
const button = ref<SubscribeButton | null>(null);

const lastCreatedId = computed(() => {
  return state.lastCreatedId
});

function openAddButton() {
    store.dispatch(addButton())
}

function closeAddButton() {
    modalOpen.value = false
    button.value = null
}

watch(lastCreatedId, (newVal, oldVal) => {
  const b : SubscribeButton = state.buttons.find((item : SubscribeButton) => Number(item.id) === newVal)
  if (b) {
    modalOpen.value = true;
    button.value = b;
  }
})
</script>
