<template>
  <Module title="Podlove Subscribe Button">
    <div class="border-b border-gray-200 pb-5 m-5">
      <p class="mt-2 text-sm text-gray-500">
        This plugin allows easy inclusion of the Podlove Subscribe Button.
      </p>
      <p class="mt-2 text-sm text-gray-500">
        The buttons are fully configured here. It is possible to insert the buttons
        into articles and posts using a Gutenberg block. However, it is also possible
        to insert the buttons with the help of the shortcode <code class="text-sm">
        [podlove-subscribe-button]</code>.
      </p>
    </div>
  </Module>
</template>

<script setup lang="ts">
import Module from '../../components/module/Module.vue'
import Settings from './components/Settings.vue'
</script>