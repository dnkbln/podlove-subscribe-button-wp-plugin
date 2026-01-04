<template>
  <div class="m-1 inline-flex">
    <span class="
      flex
      items-center
      text-xs
      font-bold
      uppercase
      px-3
      py-1
      bg-indigo-200
      text-indigo-700
      rounded-full
    ">
      {{ value }}
      <button
        class="ml-1"
        type="button"
        :aria-label="resolvedRemoveLabel"
        @click="removeTag">
        <XCircleIcon class="h-5 w-5" />
      </button>
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { XCircleIcon } from '@heroicons/vue/24/solid';

type TagId = string | number;

const props = defineProps<{
  value?: string;
  id?: TagId;
  removeLabel?: string;
}>();

const emit = defineEmits<{
  (e: 'removeTag', id: TagId): void;
}>();

const resolvedRemoveId = computed<TagId>(() => {
  if (props.id) {
    return props.id;
  }
  if (props.value) {
    return props.value;
  }
  return '';
});

const resolvedRemoveLabel = computed(() => {
  if (props.removeLabel) {
    return props.removeLabel;
  }
  if (props.value) {
    return `Remove ${props.value}`;
  }
  return undefined;
});

function removeTag() {
  emit('removeTag', resolvedRemoveId.value);
}
</script>
