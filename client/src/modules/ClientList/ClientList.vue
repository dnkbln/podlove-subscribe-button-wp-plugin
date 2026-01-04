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
        <ClientItem
          :client="client"
          :supported-platforms="supportedPlatformsFor(client)" />
      </li>
    </ul>
  </Module>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { injectStore, mapState } from 'redux-vuex';

import { selectors } from '../../store';
import Module from '../../components/module/Module.vue';
import ClientItem from './components/ClientItem.vue';
import ClientAdd, { ClientAddSelection } from './components/ClientAdd.vue';
import { add_selected_clients } from '../../store/clients.store';
import { Client } from '../../types/client.types';

const state = mapState({
  clientList: selectors.client.clientList,
  selectedClients: selectors.client.selectedClients
});

const store = injectStore();

const clientListByTitle = computed(() => {
  const map = new Map<string, Client>();
  for (const client of state.clientList ?? []) {
    if (client.title) {
      map.set(client.title, client);
    }
  }
  return map;
});

function clientKeys(client: Client): string[] {
  const keys = [];
  if (client.id) {
    keys.push(client.id);
  }
  if (client.title) {
    keys.push(client.title);
  }
  return keys;
}

function supportedPlatformsFor(client: Client): Client['platform'] {
  if (!client.title) {
    return client.platform ?? null;
  }
  const supported = clientListByTitle.value.get(client.title)?.platform;
  return supported ?? client.platform ?? null;
}

function handleClientSelect(selection: ClientAddSelection) {
  const selected = (state.selectedClients ?? []) as Client[];
  const selectedKeys = new Set<string>();
  const toAdd: Client[] = [];

  for (const client of selected) {
    for (const key of clientKeys(client)) {
      selectedKeys.add(key);
    }
  }

  if (selection.type === 'all') {
    for (const client of state.clientList ?? []) {
      const keys = clientKeys(client);
      if (!keys.some((key) => selectedKeys.has(key))) {
        for (const key of keys) {
          selectedKeys.add(key);
        }
        toAdd.push(client);
      }
    }
  } else {
    const keys = clientKeys(selection.client);
    if (!keys.some((key) => selectedKeys.has(key))) {
      for (const key of keys) {
        selectedKeys.add(key);
      }
      toAdd.push(selection.client);
    }
  }

  if (toAdd.length) {
    store.dispatch(add_selected_clients(toAdd));
  }
}

</script>
