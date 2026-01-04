<template>
  <div class="relative">
    <Popover
      :focus="true"
      panel-class="fixed inset-x-0 bottom-0 z-50 max-h-[70vh] overflow-hidden rounded-t-lg border border-gray-200 bg-white shadow-lg sm:absolute sm:right-0 sm:inset-auto sm:mt-2 sm:max-h-96 sm:w-80 sm:rounded-lg dark:border-white/10 dark:bg-gray-900">
      <template #trigger>
        <PodloveButton variant="secondary" size="small">
          <div class="inline-flex items-center">
            Add a client
            <ChevronDownIcon class="ml-2 h-4 w-4 text-indigo-700" aria-hidden="true" />
          </div>
        </PodloveButton>
      </template>

      <template #overlay="{ open, close }">
        <div
          v-if="open"
          class="fixed inset-0 z-40 bg-black/20 sm:hidden"
          aria-hidden="true"
          @click="closePopover(close)">
        </div>
      </template>

      <template #default="{ close }">
        <div class="sticky top-0 z-10 border-b border-gray-100 bg-white px-3 py-2 dark:border-white/10 dark:bg-gray-900">
          <div class="flex items-center gap-2 rounded-md border border-gray-200 bg-white px-2 py-1 dark:border-white/10 dark:bg-gray-800">
            <MagnifyingGlassIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
            <input
              v-model="query"
              type="text"
              class="w-full border-0 bg-transparent p-0 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-hidden focus:ring-0 dark:text-gray-100"
              placeholder="Search clients..."
              @keydown="handleKeydown($event, close)" />
          </div>
        </div>

        <div class="max-h-[50vh] overflow-y-auto sm:max-h-72">
          <ul role="listbox" class="py-2">
            <li v-if="allOption" :id="allOption.id" role="option" :aria-selected="activeIndex === 0">
              <button
                type="button"
                class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-800"
                :class="activeIndex === 0 ? 'bg-gray-50 dark:bg-gray-800' : ''"
                @click="selectItem(close)">
                <span class="inline-flex size-8 items-center justify-center rounded-md bg-indigo-100 text-indigo-700">
                  <GlobeAltIcon class="h-4 w-4" aria-hidden="true" />
                </span>
                <span>All clients</span>
              </button>
            </li>
            <li
              v-for="(item, index) in filteredClients"
              :key="itemKey(item)"
              :id="itemKey(item)"
              role="option"
              :aria-selected="activeIndex === indexOffset + index">
              <button
                type="button"
                class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-800"
                :class="activeIndex === indexOffset + index ? 'bg-gray-50 dark:bg-gray-800' : ''"
                @click="selectClient(item, close)">
                <span
                  class="inline-flex size-8 items-center justify-center rounded-md bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                  <img v-if="clientIcon(item)" :src="clientIcon(item)" :alt="`${item.title} icon`" class="h-5 w-5" />
                  <span v-else class="text-xs font-semibold">{{ clientInitial(item.title) }}</span>
                </span>
                <span class="truncate">{{ item.title }}</span>
              </button>
            </li>
            <li v-if="!filteredClients.length" class="px-4 py-2 text-sm text-gray-500">
              No matching clients
            </li>
          </ul>
        </div>
      </template>
    </Popover>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { ChevronDownIcon, GlobeAltIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import PodloveButton from '../../../components/button/Button.vue';
import { Client } from '../../../types/client.types';
import Popover from '../../../components/popover/Popover.vue';

import antennaPodIcon from '../../../assets/antennapod/icon.svg';
import applePodcastsIcon from '../../../assets/apple-podcasts/icon.svg';
import gpodderIcon from '../../../assets/gpodder/icon.svg';

export type ClientAddSelection =
  | { type: 'all' }
  | { type: 'client'; client: Client };

const props = defineProps<{
  clients: Client[];
  selectedClients: Client[];
}>();

const emit = defineEmits<{
  (e: 'select', selection: ClientAddSelection): void;
}>();

const query = ref('');
const activeIndex = ref(0);

const selectedKeys = computed(() => {
  const keys = new Set<string>();
  for (const client of props.selectedClients ?? []) {
    if (client.id) {
      keys.add(client.id);
    }
    if (client.title) {
      keys.add(client.title);
    }
  }
  return keys;
});

const availableClients = computed(() => {
  return (props.clients ?? []).filter((client) => {
    if (client.id && selectedKeys.value.has(client.id)) {
      return false;
    }
    if (client.title && selectedKeys.value.has(client.title)) {
      return false;
    }
    return true;
  });
});

const filteredClients = computed(() => {
  const term = query.value.trim().toLowerCase();
  if (!term) {
    return availableClients.value;
  }
  return availableClients.value.filter((client) =>
    (client.title ?? '').toLowerCase().includes(term)
  );
});

const allOption = computed(() => {
  if (!availableClients.value.length) {
    return null;
  }
  return { id: 'all-clients' };
});

const indexOffset = computed(() => (allOption.value ? 1 : 0));

function clientKey(client: Client): string {
  return client.id ?? client.title ?? '';
}

function itemKey(client: Client): string {
  return `client-${clientKey(client)}`;
}

function clientInitial(title: string | null): string {
  if (!title) {
    return '?';
  }
  return title.slice(0, 1).toUpperCase();
}

function clientIcon(client: Client): string | undefined {
  switch (client.title) {
    case 'AntennaPod':
      return antennaPodIcon;
    case 'Apple Podcasts':
      return applePodcastsIcon;
    case 'gPodder':
      return gpodderIcon;
    default:
      return undefined;
  }
}

function resetState() {
  query.value = '';
  activeIndex.value = 0;
}

function closePopover(close: () => void) {
  close();
  resetState();
}

function selectItem(close: () => void) {
  emit('select', { type: 'all' });
  closePopover(close);
}

function selectClient(client: Client, close: () => void) {
  emit('select', { type: 'client', client });
  closePopover(close);
}

function handleKeydown(event: KeyboardEvent, close: () => void) {
  const itemsCount = filteredClients.value.length + (allOption.value ? 1 : 0);
  if (!itemsCount) {
    if (event.key === 'Escape') {
      event.preventDefault();
      closePopover(close);
    }
    return;
  }

  if (event.key === 'ArrowDown') {
    event.preventDefault();
    activeIndex.value = (activeIndex.value + 1) % itemsCount;
    return;
  }

  if (event.key === 'ArrowUp') {
    event.preventDefault();
    activeIndex.value = (activeIndex.value - 1 + itemsCount) % itemsCount;
    return;
  }

  if (event.key === 'Enter') {
    event.preventDefault();
    if (allOption.value && activeIndex.value === 0) {
      selectItem(close);
      return;
    }
    const clientIndex = activeIndex.value - indexOffset.value;
    const client = filteredClients.value[clientIndex];
    if (client) {
      selectClient(client, close);
    }
    return;
  }

  if (event.key === 'Escape') {
    event.preventDefault();
    closePopover(close);
  }
}

watch([filteredClients, allOption], () => {
  const itemsCount = filteredClients.value.length + (allOption.value ? 1 : 0);
  if (activeIndex.value >= itemsCount) {
    activeIndex.value = 0;
  }
});
</script>
