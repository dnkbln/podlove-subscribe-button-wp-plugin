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
          @click="removeClient()">
          <trash-icon class="h-5 w-5" aria-hidden="true" />
        </button>
      </div>
    </header>

    <div class="mt-auto border-t border-gray-100 px-4 py-3 dark:border-white/10">
      <div class="flex items-start gap-2">
        <div class="flex flex-wrap">
          <PodloveTag
            v-for="platform in visiblePlatforms"
            :key="platform"
            :id="platform"
            :value="platformLabel(platform)"
            :remove-label="`Remove ${platformLabel(platform)}`"
            @removeTag="handleRemoveTag" />
          <p v-if="!visiblePlatforms.length" class="text-xs text-gray-400">No platforms</p>
        </div>
        <div class="relative ml-auto">
          <Popover
            panel-class="absolute right-0 z-10 mt-2 w-44 rounded-md border border-gray-200 bg-white shadow-lg dark:border-white/10 dark:bg-gray-900">
            <template #trigger>
              <button
                type="button"
                class="inline-flex size-8 items-center justify-center rounded-md border border-gray-200 text-gray-500 transition hover:bg-gray-50 hover:text-gray-900 disabled:cursor-not-allowed disabled:opacity-50 dark:border-white/10 dark:text-gray-300 dark:hover:bg-gray-800"
                :disabled="!missingPlatforms.length"
                :aria-label="missingPlatforms.length ? 'Add platform' : 'All platforms added'">
                <plus-icon class="h-4 w-4" aria-hidden="true" />
              </button>
            </template>
            <template #default="{ close }">
              <ul class="py-1">
                <li
                  v-for="platform in missingPlatforms"
                  :key="platform">
                  <button
                    type="button"
                    class="flex w-full items-center px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800"
                    @click="addPlatform(platform, close)">
                    {{ platformLabel(platform) }}
                  </button>
                </li>
                <li v-if="!missingPlatforms.length" class="px-3 py-2 text-xs text-gray-500">
                  All platforms added
                </li>
              </ul>
            </template>
          </Popover>
        </div>
      </div>
    </div>
  </article>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Client } from '../../../types/client.types';
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { injectStore } from 'redux-vuex';

import { remove_selected_client, update_selected_client } from '../../../store/clients.store';
import Popover from '../../../components/popover/Popover.vue';
import PodloveTag from '../../../components/tag/Tag.vue';

import antennaPodIcon  from '../../../assets/antennapod/icon.svg';
import applePodcastsIcon  from '../../../assets/apple-podcasts/icon.svg';
import gpodderIcon from '../../../assets/gpodder/icon.svg';

const store = injectStore();

const props = defineProps<{
  client: Client;
  supportedPlatforms: Client['platform'];
}>();

function removeClient() {
  store.dispatch(remove_selected_client(props.client))
}

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

const visiblePlatforms = computed(() => normalizePlatforms(props.client.platform));
const supportedPlatformList = computed(() => normalizePlatforms(props.supportedPlatforms ?? props.client.platform));
const missingPlatforms = computed(() =>
  supportedPlatformList.value.filter((platform) => !visiblePlatforms.value.includes(platform))
);

function removePlatform(platform: string) {
  const updatedPlatforms = visiblePlatforms.value.filter((entry) => entry !== platform);
  store.dispatch(update_selected_client({
    id: props.client.id,
    prop: 'platform',
    value: updatedPlatforms
  }));
}

function handleRemoveTag(id: string | number) {
  removePlatform(String(id));
}

function addPlatform(platform: string, close: () => void) {
  const updatedPlatforms = Array.from(new Set([...visiblePlatforms.value, platform]));
  store.dispatch(update_selected_client({
    id: props.client.id,
    prop: 'platform',
    value: updatedPlatforms
  }));
  close();
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
