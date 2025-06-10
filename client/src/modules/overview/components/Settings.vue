<template>
    <podlove-button variant="secondary" size="small" @click="openSettings()">Seetings</podlove-button>
    <Modal :open="modalEdit" @close="closeSettings()">
        <h1>Settings Page</h1>
    </Modal>
</template>

<script setup lang="ts">
import { mapState } from 'redux-vuex';
import { selectors } from '../../../store';

import Modal from '../../../components/modal/Modal.vue';
import PodloveButton from '../../../components/button/Button.vue'
import { ref } from 'vue'

const state = mapState({
  settings: selectors.settings.settings
});

const modalEdit = ref(false);

function openSettings() {
    modalEdit.value = true
}

function closeSettings() {
    modalEdit.value = false
}
</script>