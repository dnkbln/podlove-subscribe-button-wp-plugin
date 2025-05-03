<template>
  <Module title="Podlove Subscribe Button">
    <template v-slot:actions>
      <settings></settings>
    </template>

    <div class="border-b border-gray-200 pb-5 m-5">
      <p class="mt-2 max-w-4xl text-sm text-gray-500">
        This plugin allows easy inclusion of the Podlove Subscribe Button. Put it in your sidebar with a simple widget or
        include the button in pages and/or posts with a simple shortcode. Start by adding a button for each of your podcasts
        here. You can then add the button to your sidebar by adding the Podlove Subscribe Button widget.
        If you want to display the button inside a page or article, you can also use the [podlove-subscribe-button]
        shortcode anywhere.
      </p>
    </div>
  </Module>
</template>

<script setup lang="ts">
import Module from '../../components/module/Module.vue'
import Settings from './components/Settings.vue'
</script>