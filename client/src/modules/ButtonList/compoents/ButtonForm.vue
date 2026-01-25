<template>
  <div>
    <div class="pb-2 sm:pb-0">
      <h3 class="text-base/7 font-semibold text-gray-900">Podcast informations</h3>
    </div>
    <div class="p-3">
      <div class="flex justify-items-stretch mb-2">
        <PodcastCover class="mr-5"/>
        <div class="mb-2 w-full">
          <ButtonID :button="button" class="w-full" />
          <PodcastTitle :button="button" class="mb-2"></PodcastTitle>
        </div>
      </div>
      <PodcastSubtitle :button="button" class="mb-2"></PodcastSubtitle>
      <PodcastDescription :button="button" class="mb-2"></PodcastDescription>
      <PodcastFeed :button="button"></PodcastFeed>
    </div>
    <div class="pb-2 sm:pb-0">
      <h3 class="text-base/7 font-semibold text-gray-900">Client selection</h3>
    </div>
    <div class="p-3">
       <ClientList v-if="Number.isFinite(resolvedButtonId)" :button-id="resolvedButtonId" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue';
import { injectStore } from 'redux-vuex';
import ButtonID from './ButtonID.vue';
import PodcastTitle from './PodcastTitle.vue';
import PodcastSubtitle from './PodcastSubtitle.vue';
import PodcastDescription from './PodcastDescription.vue';
import PodcastCover from './PodcastCover.vue';
import PodcastFeed from './PodcastFeed.vue';
import ClientList from '../../ClientList/ClientList.vue';

import type { SubscribeButton } from '@app-types/buttons.types'
import { fetch_selected_clients } from '@store/clients.store';

const props = defineProps<{
  button: SubscribeButton;
}>();

const store = injectStore();
const resolvedButtonId = computed(() => Number(props.button.id));

watch(
  () => resolvedButtonId.value,
  (value) => {
    if (Number.isFinite(value)) {
      store.dispatch(fetch_selected_clients({ buttonId: value }));
    }
  },
  { immediate: true }
);

</script>
