<template>
  <Module title="Clients">
    <template v-slot:actions>
      <ClientAdd
        :clients="state.clientList"
        :selected-clients="state.selectedClients"
        @select="handleClientSelect" />
    </template>
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
      <li v-for="client in state.selectedClients" :key="client.id" class="col-span-1">
        <ClientItem :client="client" />
      </li>
    </ul>
  </Module>
</template>

<script setup lang="ts">
import { injectStore, mapState } from 'redux-vuex';

import { selectors } from '../../store';
import Module from '../../components/module/Module.vue';
import ClientItem from './components/ClientItem.vue';
import ClientAdd, { ClientAddSelection } from './components/ClientAdd.vue';
import { set_selected_clients } from '../../store/clients.store';
import { Client } from '../../types/client.types';

const state = mapState({
  clientList: selectors.client.clientList,
  selectedClients: selectors.client.selectedClients
});

const store = injectStore();

function clientKey(client: Client): string {
  return client.id ?? client.title ?? '';
}

function handleClientSelect(selection: ClientAddSelection) {
  const selected = (state.selectedClients ?? []) as Client[];
  const selectedByKey = new Map<string, Client>();

  for (const client of selected) {
    selectedByKey.set(clientKey(client), client);
  }

  if (selection.type === 'all') {
    for (const client of state.clientList ?? []) {
      const key = clientKey(client);
      if (!selectedByKey.has(key)) {
        selectedByKey.set(key, client);
      }
    }
  } else {
    const key = clientKey(selection.client);
    if (!selectedByKey.has(key)) {
      selectedByKey.set(key, selection.client);
    }
  }

  store.dispatch(set_selected_clients(Array.from(selectedByKey.values())));
}

</script>
