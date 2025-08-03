<template>
  <Module title="Clients">
    <div class="border-b border-gray-200 pb-5 m-5">
      <p class="mt-2 text-sm text-gray-500">
        Here you can select the apps and services you want to offer in the SubscribeButton.
      </p>
      <p class="mt-2 text-sm text-gray-500">
        Some apps and services require you to provide information. This information is requested when
        creating buttons for the selected apps and services.
      </p>
    </div>
    <div class="border-b border-gray-200 pb-5 m-5">
      <ClientItems></ClientItems>
    </div>
  </Module>
</template>

<script setup lang="ts">
import Module from '../../components/module/Module.vue';
import ClientItems from './components/ClientItems.vue';

</script>
