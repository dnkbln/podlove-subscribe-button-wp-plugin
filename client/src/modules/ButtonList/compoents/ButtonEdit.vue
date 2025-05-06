<template>
    <p>Edit Button: {{ button.id }}</p>
    <p> {{ button.name }}</p>
</template>

<script setup lang="ts">
import { SubscribeButton } from '../../../types/buttons.types'

const props = defineProps<{
  button: SubscribeButton;
}>();

</script>