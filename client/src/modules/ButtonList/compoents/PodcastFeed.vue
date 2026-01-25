<template>
  <div>
    <label for="feed-url" class="block text-sm font-medium text-gray-700">Feed url
    </label>
    <div class="mt-1">
      <input id="feed-url" name="feed-url" maxlength="250" class="
          shadow-sm
          focus:ring-indigo-500 focus:border-indigo-500
          p-2
          mt-1
          block
          w-full
          sm:text-sm
          border border-gray-300
          rounded-md
          resize-y
        " :value="feedUrl" @input="changeFeeds($event)"> </input>
    </div>
    <p class="mt-2 text-sm text-gray-500 flex justify-between">
      <span>Podcast feed</span>
    </p>

  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { injectStore } from 'redux-vuex';
import { SubscribeButton, Feed } from '../../../types/buttons.types'
import { updateItem as updateButtonItem } from '@store/buttons.store';

const props = defineProps<{
    button: SubscribeButton;
}>();

const selected = ref(true);

const store = injectStore();

const feedUrl = computed(() => {
    return props.button.feeds ? props.button.feeds[0]?.url ?? '' : '';
});

const changeFeeds = (event: Event) => {
    let feeds : Feed[] = new Array<Feed>({ url: (event.target as HTMLInputElement).value, itunesfeedid: null, format: 'mp3' });

    store.dispatch(
      updateButtonItem({id: props.button.id, prop: 'feeds', value: feeds})
    )
}
</script>
