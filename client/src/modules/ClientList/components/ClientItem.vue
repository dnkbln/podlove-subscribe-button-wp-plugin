<template>
  <div class="shrink-0">
    <img
      class="size-10 rounded-full bg-gray-300 outline -outline-offset-1 outline-black/5 dark:bg-gray-700 dark:outline-white/10"
      :src="iconPath"
      :alt="`${props.client.title} Icon`" />
  </div>
  <div
    class="flex flex-1 items-center justify-between truncate rounded-r-md border-t border-r border-b border-gray-200 bg-white dark:border-white/10 dark:bg-gray-800/50">
    <div class="flex-1 truncate px-4 py-2 text-sm">
      <p class="text-gray-500 dark:text-gray-400">{{ props.client.title }} </p>
    </div>
    <div class="shrink-0 pr-2">
      <button @click="removeClient(props.client.id)">
        <trash-icon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true"> Remove Client </trash-icon>
      </button>
    </div>
  </div>

</template>

<script setup lang="ts">
import { Client } from '../../../types/client.types';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { injectStore } from 'redux-vuex';

import { remove_selected_client } from '../../../store/clients.store';

import antennaPodIcon  from '../../../assets/antennapod/icon.svg';
import applePodcastsIcon  from '../../../assets/apple-podcasts/icon.svg';
import gpodderIcon from '../../../assets/gpodder/icon.svg';

const store = injectStore();

const props = defineProps<{
  client: Client
}>();

function removeClient(id: string) {
  console.log("Remove client with ID:", id);
  store.dispatch(remove_selected_client(props.client))
}

function getClientIconPath(title: string | null): string {
  switch (title) {
    case 'AntennaPod':
      return antennaPodIcon;
    case 'Apple Podcasts':
      return applePodcastsIcon;
    case 'gPodder':
      return gpodderIcon;
    default:
      return 'https://via.placeholder.com/40'; // Fallback icon URL
  }
}

const iconPath = getClientIconPath(props.client.title);
</script>