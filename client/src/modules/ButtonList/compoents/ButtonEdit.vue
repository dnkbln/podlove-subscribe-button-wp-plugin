<template>
  <div>
    <div class="px-4 sm:px-0">
      <h3 class="text-base/7 font-semibold text-gray-900">Podlove Subscribe Button</h3>
      <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Podcast informations</p>
    </div>
    <div class="p-3">
      <div class="flex justify-items-stretch mb-5">
        <PodcastCover class="mr-5"/>
        <div class="mb-2 w-full">
          <ButtonID :button="button" class="w-full" />
          <PodcastTitle :button="button" class="mb-5"></PodcastTitle>
        </div>
      </div>
      <PodcastSubtitle :button="button" class="mb-5"></PodcastSubtitle>
      <PodcastDescription :button="button" class="mb-5"></PodcastDescription>
      <PodcastFeed :button="button"></PodcastFeed>
    </div>
  </div>
</template>

<script setup lang="ts">
import ButtonID from './ButtonID.vue';
import PodcastTitle from './PodcastTitle.vue';
import PodcastSubtitle from './PodcastSubtitle.vue';
import PodcastDescription from './PodcastDescription.vue';
import PodcastCover from './PodcastCover.vue';
import PodcastFeed from './PodcastFeed.vue';

import { SubscribeButton } from '../../../types/buttons.types'

const props = defineProps<{
  button: SubscribeButton;
}>();

</script>