<template>
  <li class="flex items-center gap-5 py-2">
    <div class="flex-1 min-w-0">
      <p class="text-sm font-semibold text-gray-900">{{ button.title }}</p>
      <p class="mt-1 text-xs text-gray-500">[podlove-subscribe-button button="{{ button.name }}"]</p>
    </div>

    <div class="w-28 h-8 flex justify-center items-center">
      <ButtonPreview :button="props.button" :key="props.button.id"></ButtonPreview>
    </div>

    <div class="w-28 h-8 flex justify-end items-center gap-2">
      <button type="button" class="text-gray-500 hover:text-gray-900 p-2" @click="toggleEdit" aria-label="Edit">
        <PencilSquareIcon class="h-5 w-5" />
      </button>
      <button type="button" class="text-gray-500 hover:text-red-600 p-2" @click="deleteButtonItem" aria-label="Delete">
        <TrashIcon class="h-5 w-5" />
      </button>
    </div>

  </li>
  <Disclosure :open="isOpen" @close="closeEdit">
    <template #default>
      <li class="px-4 py-3 bg-gray-50 border-t border-gray-100">
        <ButtonForm :button="props.button" @close="closeEdit"></ButtonForm>
      </li>
    </template>
  </Disclosure>
</template>

<script setup lang="ts">

import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { ref, watch } from 'vue'
import { injectStore } from 'redux-vuex';

import { SubscribeButton } from '../../../types/buttons.types'
import ButtonForm from './ButtonForm.vue'
import ButtonPreview from './ButtonPreview.vue'
import { deleteButton } from '@store/buttons.store';
import Disclosure from '@components/disclosure/Disclosure.vue';

const isOpen = ref(false);
const store = injectStore();

const emit = defineEmits<{
  (e: 'closed', id: string): void
  (e: 'opened', id: string): void
}>()

const props = defineProps<{
  button: SubscribeButton;
  open?: boolean;
}>();

watch(() => props.open, (val) => {
  if (typeof val === 'boolean') {
    isOpen.value = val
  }
}, { immediate: true })

function toggleEdit() {
    isOpen.value = !isOpen.value
    if (isOpen.value) {
      emit('opened', props.button.id)
    } else {
      emit('closed', props.button.id)
    }
}

function closeEdit() {
    isOpen.value = false
    emit('closed', props.button.id)
}

function deleteButtonItem() {
  store.dispatch( deleteButton(props.button) )
}

</script>
