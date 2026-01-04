<template>
  <Popover v-slot="{ open, close }">
    <PopoverButton as="template">
      <slot name="trigger" :open="open" :close="close" />
    </PopoverButton>

    <slot name="overlay" :open="open" :close="close" />

    <TransitionRoot as="template"
      enter="transition duration-150 ease-out"
      enter-from="opacity-0 translate-y-1"
      enter-to="opacity-100 translate-y-0"
      leave="transition duration-100 ease-in"
      leave-from="opacity-100 translate-y-0"
      leave-to="opacity-0 translate-y-1"
      :show="panelStatic ? true : open">
      <PopoverPanel
        :static="panelStatic"
        :focus="focus"
        :class="panelClass">
        <slot :open="open" :close="close" />
      </PopoverPanel>
    </TransitionRoot>
  </Popover>
</template>

<script setup lang="ts">
import { Popover, PopoverButton, PopoverPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';

withDefaults(defineProps<{
  panelClass?: string;
  panelStatic?: boolean;
  focus?: boolean;
}>(), {
  panelClass: '',
  panelStatic: false,
  focus: false
});
</script>
