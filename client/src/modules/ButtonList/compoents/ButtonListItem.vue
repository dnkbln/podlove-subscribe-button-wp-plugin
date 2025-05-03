<template>
  <li class="flex items-center gap-5 py-2">
    <div class="flex-1 min-w-0">
      <p class="text-sm font-semibold text-gray-900">{{ button.title }}</p>
      <p class="mt-1 text-xs text-gray-500">[podlove-subscribe-button button="{{ button.name }}"]</p>
    </div>

    <div class="w-24 text-center">
      <MegaphoneIcon class="inline-block w-10" />
    </div>

    <div class="w-16 text-right">
      <Menu as="div" class="relative inline-block text-left">
        <MenuButton class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">
          <span class="sr-only">Open options</span>
          <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
        </MenuButton>
        <Transition enter="transition ease-out duration-100" enter-from="transform opacity-0 scale-95"
          enter-to="transform opacity-100 scale-100" leave="transition ease-in duration-75"
          leave-from="transform opacity-100 scale-100" leave-to="transform opacity-0 scale-95">
          <MenuItems
            class="absolute right-0 mt-2 w-56 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg outline-none z-50">
            <div class="px-1 py-1">
              <MenuItem v-slot="{ active }">
              <button :class="[
                'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                active ? 'bg-blue-100 text-blue-900' : 'text-gray-900'
              ]">
                Edit
              </button>
              </MenuItem>
              <MenuItem v-slot="{ active }">
              <button :class="[
                'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                active ? 'bg-blue-100 text-blue-900' : 'text-gray-900'
              ]">
                Delete
              </button>
              </MenuItem>
            </div>
          </MenuItems>
        </Transition>
      </Menu>
    </div>
  </li>

</template>

<script setup lang="ts">
import { ref } from 'vue'

import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon, MegaphoneIcon } from '@heroicons/vue/20/solid'

import { SubscribeButton } from '../../../types/buttons.types'

const props = defineProps<{
  button: SubscribeButton;
}>();

</script>
