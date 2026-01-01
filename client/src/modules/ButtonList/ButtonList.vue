<template>
  <Module title="Buttons">
    <template v-slot:actions>
      <button-add></button-add>
    </template>
    <div class="bg-white px-4 pt-12 pb-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-4xl">
        <div class="flex items-center gap-4 py-2 text-xs font-medium text-gray-500">
          <div class="flex-1 min-w-0">
            <div class="flex flex-col">
              <span>Title</span>
              <span class="text-gray-400">Shortcode</span>
            </div>
          </div>

          <div class="w-28 flex justify-center items-center">
            Preview
          </div>

          <div class="w-28 flex justify-end items-center">
            Aktionen
          </div>
        </div>
        <ul role="list" class="divide-y divide-gray-100">
          <ButtonListItem
            v-for="button in state.buttons"
            :key="button.id"
            :button="button"
            :open="openId === String(button.id)"
            @closed="handleClosed"
          ></ButtonListItem>
        </ul>
      </div>
    </div>
  </Module>
</template>

<script setup lang="ts">
import { mapState } from 'redux-vuex';
import { ref, watch } from 'vue';
import { selectors } from '../../store';

import ButtonListItem from './compoents/ButtonListItem.vue';
import ButtonAdd from './compoents/ButtonAdd.vue';
import Module from '../../components/module/Module.vue'

const state = mapState({
  buttons: selectors.buttons.buttons,
  lastCreatedId: selectors.buttons.lastCreatedId
});

const openId = ref<string | null>(null)

watch(() => state.lastCreatedId, (newVal) => {
  if (newVal) {
    openId.value = newVal ? String(newVal) : null
  }
})

function handleClosed(id: string) {
  if (openId.value === id) {
    openId.value = null
  }
}

</script>
