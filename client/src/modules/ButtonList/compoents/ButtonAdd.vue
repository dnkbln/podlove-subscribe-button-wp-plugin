<template>
    <podlove-button variant="secondary" size="small" @click="openAddButton()">Add new</podlove-button>
</template>

<script setup lang="ts">
import { injectStore } from 'redux-vuex';

import PodloveButton from '@components/button/Button.vue'
import { add as addButton } from '@store/buttons.store';

const store = injectStore();

function openAddButton() {
    store.dispatch(addButton())
}
</script>
