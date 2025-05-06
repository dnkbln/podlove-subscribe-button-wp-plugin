<template>
    <podlove-button variant="secondary" size="small" @click="openAddButton()">Add new</podlove-button>
    <Modal :open="modalOpen" @close="closeAddButton()">
        <h1>Add New Button Page</h1>
    </Modal>
</template>

<script setup lang="ts">
import Modal from '../../../components/modal/Modal.vue';
import PodloveButton from '../../../components/button/Button.vue'
import { ref } from 'vue'

const modalOpen = ref(false);

function openAddButton() {
    modalOpen.value = true
}

function closeAddButton() {
    modalOpen.value = false
}
</script>
