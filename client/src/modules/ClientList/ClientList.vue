<template>
  <Module title="Clients">
    <div class="border-b border-gray-200 pb-5 m-5">
      <p class="mt-2 text-sm text-gray-500">
        Here you can select the apps and services you want to offer in the SubscribeButton.
      </p>
      <p class="mt-2 text-sm text-gray-500">
        Some apps and services require you to provide information. This information is requested when
        creating buttons for the selected apps and services.
      </p>
    </div>
    <ul role="list" class="m-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
      <li v-for="client in state.selectedClients" :key="client.id"
        class="col-span-1 flex rounded-md shadow-xs dark:shadow-none">
        <ClientItem :client="client" />
      </li>
    </ul>
    <div class="py-3 px-6 border-t border-gray-200">
      <ul v-if="addClientInput">
        <li class="mb-0">
          <AddClient @addClient="addClient($event)" @close="closeAddClient()" />
        </li>
      </ul>
      <div v-if="!addClientInput" class="py-3">
        <podlove-button variant="secondary" @click="showAddClient()">
          <plus-sm-icon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true" /> Add Client
        </podlove-button>
      </div>
    </div>
  </Module>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { mapState } from 'redux-vuex';
import { PlusIcon as PlusSmIcon } from '@heroicons/vue/24/outline'

import { selectors } from '../../store';
import Module from '../../components/module/Module.vue';
import ClientItem from './components/ClientItem.vue';
import PodloveButton from '../../components/button/Button.vue'
import AddClient from './components/AddClient.vue';

const addClientInput = ref(false)

const state = mapState({
  clientList: selectors.client.clientList,
  selectedClients: selectors.client.selectedClients
});

function closeAddClient() {
  addClientInput.value = false
}

function showAddClient() {
  addClientInput.value = true
}

function addClient(event: Event) {
  console.log("Add client", event)
}

</script>
