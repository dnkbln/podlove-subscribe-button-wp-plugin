<template>
    <div :id="`podlove-button-${props.button.id}`" class="mb-4"></div>
</template>

<script setup lang="ts">
import { onMounted, nextTick } from 'vue'
import { SubscribeButton } from '../../../types/buttons.types'

import { mapState } from 'redux-vuex';
import { selectors } from '../../../store';


const state = mapState({
  settings: selectors.settings.settings
});

const props = defineProps<{
    button: SubscribeButton;
}>()

onMounted(async () => {
    await nextTick()

    const dataVar = 'podcastData_' + props.button.id
    ;(window as any)[dataVar] = {
        title: props.button.title ?? '',
        subtitle: props.button.subtitle ?? '',
        description: props.button.description ?? '',
        cover: props.button.cover ?? '',
        feeds: props.button.feeds
    }

    const script = document.createElement('script')
    script.className = 'podlove-subscribe-button'
    script.src = 'https://cdn.podlove.org/subscribe-button/javascripts/app.js'
    script.setAttribute('data-size', state.settings.size)
    script.setAttribute('data-style', state.settings.style)
    script.setAttribute('data-format', state.settings.format)
    script.setAttribute('data-color', state.settings.color)
    script.setAttribute('data-json-data', dataVar)
    script.setAttribute('data-language', 'en')

    const container = document.getElementById(`podlove-button-${props.button.id}`)
    container?.appendChild(script)
})
</script>
