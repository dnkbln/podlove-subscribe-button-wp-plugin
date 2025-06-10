<template>
  <div>
    <label for="subtitle" class="block text-sm font-medium text-gray-700">Feeds
    </label>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { injectStore } from 'redux-vuex';
import { SubscribeButton } from '../../../types/buttons.types'

const props = defineProps<{
    button: SubscribeButton;
}>();

const selected = ref(true);

const store = injectStore();
</script>
