<template>
  <article
    class="flex h-full flex-col rounded-lg border border-gray-200 bg-white shadow-xs transition hover:shadow-sm dark:border-white/10 dark:bg-gray-900">
    <header class="flex items-start justify-between gap-3 p-4">
      <div class="flex items-center gap-3">
        <img
          class="size-12 rounded-md bg-gray-100 object-contain p-1 outline -outline-offset-1 outline-black/5 dark:bg-gray-800 dark:outline-white/10"
          :src="iconPath"
          :alt="`${props.client.title} Icon`" />
        <div class="min-w-0">
          <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">
            {{ props.client.title }}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Client</p>
        </div>
        <button
          type="button"
          class="inline-flex size-12 items-center justify-center rounded-md text-gray-500 hover:text-red-600"
          aria-label="Remove client"
          @click="removeClient(props.client.id)">
          <trash-icon class="h-5 w-5" aria-hidden="true" />
        </button>
      </div>
    </header>

    <div class="mt-auto border-t border-gray-100 px-4 py-3 dark:border-white/10">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="platform in visiblePlatforms"
          :key="platform"
          type="button"
          class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200"
          :aria-label="`Remove ${platformLabel(platform)}`"
          @click="removePlatform(platform)">
          <span>{{ platformLabel(platform) }}</span>
          <x-icon class="h-3.5 w-3.5" aria-hidden="true" />
        </button>
        <p v-if="!visiblePlatforms.length" class="text-xs text-gray-400">No platforms</p>
      </div>
    </div>
  </article>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { Client } from '../../../types/client.types';
import { TrashIcon, XMarkIcon as XIcon } from '@heroicons/vue/24/outline';
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

const hiddenPlatforms = ref<string[]>([]);

const platformLabels: Record<string, string> = {
  android: 'Android',
  ios: 'iOS',
  osx: 'macOS',
  windows: 'Windows',
  unix: 'Unix',
  web: 'Web'
};

function platformLabel(platform: string): string {
  return platformLabels[platform] ?? platform.toUpperCase();
}

function normalizePlatforms(platform: Client['platform'] | string[] | null): string[] {
  if (!platform) {
    return [];
  }

  if (Array.isArray(platform)) {
    return platform;
  }

  if (platform.includes(',')) {
    return platform.split(',').map((entry) => entry.trim()).filter(Boolean);
  }

  return [platform];
}

const visiblePlatforms = computed(() => {
  const platforms = normalizePlatforms(props.client.platform);
  return platforms.filter((platform) => !hiddenPlatforms.value.includes(platform));
});

function removePlatform(platform: string) {
  if (!hiddenPlatforms.value.includes(platform)) {
    hiddenPlatforms.value = [...hiddenPlatforms.value, platform];
  }
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

const iconPath = computed(() => getClientIconPath(props.client.title));
</script>
