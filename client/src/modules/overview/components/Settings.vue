<template>
    <podlove-button variant="secondary" size="small" @click="openSettings()">Seetings</podlove-button>
    <Modal :open="modalOpen" @close="closeSettings()">
        <H1>Settings Page</H1>
    </Modal>
</template>

<script setup lang="ts">
import Modal from '../../../components/modal/Modal.vue';
import PodloveButton from '../../../components/button/Button.vue'
import { ref } from 'vue'

const modalOpen = ref(false);

function openSettings() {
    modalOpen.value = true
}

function closeSettings() {
    modalOpen.value = false
}
</script>