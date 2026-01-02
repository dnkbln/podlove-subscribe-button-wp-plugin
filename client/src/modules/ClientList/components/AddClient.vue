<template>
  <div class="block hover:bg-gray-50">
    <div class="flex items-center px-4 py-4 sm:px-6">
      <div class="flex min-w-0 flex-1 items-center">
        <PodloveListbox
          class="w-1/2"
          :options="fullClientList()"
          :multiple="true"
          @update="updateClientList($event)"
          placeholder="Select a client..." />
      </div>
      <div class="flex space-x-2 justify-end">
        <button class="text-red-600" @click="close()">
          <x-icon class="w-5 h-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { mapState } from 'redux-vuex'
import { XMarkIcon as XIcon } from '@heroicons/vue/24/outline';
import PodloveListbox, { OptionObject } from '../../../components/combobox/Combobox.vue'

import { selectors } from '../../../store';

const state = mapState({
  clientList: selectors.client.clientList,
  selectedClients: selectors.client.selectedClients
})

const emit = defineEmits<{
  (e: 'addClient', client: any): void;
  (e: 'close'): void;
}>()

function close() {
  emit('close')
}

function fullClientList(): Array<OptionObject> {
  const filteredList = state.clientList.filter(
    (client: OptionObject) =>
      !state.selectedClients.some(
        (selected: OptionObject) => selected.title === client.title
      )
  );
  return filteredList;
}

function updateClientList(addedClients: Array<OptionObject>) {
  console.log("Update client list", addedClients)
}

</script>