<template>
  <!-- Extern gesteuerte Disclosure: rendert nur Inhalt, wenn open === true -->
  <slot name="trigger" :open="open" :close="emitClose" />
  <div v-if="open" class="w-full">
    <slot :close="emitClose" />
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{
  open?: boolean
}>(), {
  open: false
});

const emit = defineEmits<{
  (e: 'close'): void
}>()

function emitClose() {
  emit('close')
}
</script>
