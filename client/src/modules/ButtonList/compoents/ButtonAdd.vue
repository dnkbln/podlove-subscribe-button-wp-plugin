<template>
    <podlove-button variant="secondary" size="small" @click="openAddButton()">Add new</podlove-button>
    <Modal size="medium" :open="modalOpen" @close="closeAddButton()">
        <div>
            <div class="px-4 sm:px-0">
                <h3 class="text-base/7 font-semibold text-gray-900">Podlove Subscribe Button</h3>
                <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Podcast informations</p>
                <div class="p-3">
                    <ButtonID :button="button" class="mb-5"></ButtonID>
                    <PodcastTitle :button="button" class="mb-5"></PodcastTitle>
                    <PodcastSubtitle :button="button" class="mb-5"></PodcastSubtitle>
                    <PodcastDescription :button="button" class="mb-5"></PodcastDescription>
                </div>
            </div>
        </div>

    </Modal>
</template>

<script setup lang="ts">
import Modal from '../../../components/modal/Modal.vue';
import PodloveButton from '../../../components/button/Button.vue'
import ButtonID from './ButtonID.vue';
import PodcastTitle from './PodcastTitle.vue';
import PodcastSubtitle from './PodcastSubtitle.vue';
import PodcastDescription from './PodcastDescription.vue';

import { SubscribeButton } from 'src/types/buttons.types';
import { reactive, ref } from 'vue'

const modalOpen = ref(false);
const button = reactive(({id: "10", name: null, title: null, subtitle: null, description: null, cover: null, feeds: []}) as SubscribeButton)

function openAddButton() {
    modalOpen.value = true
}

function closeAddButton() {
    modalOpen.value = false
}
</script>
