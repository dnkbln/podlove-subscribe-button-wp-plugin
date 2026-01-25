<template>
    <div>
        <label class="block text-sm font-medium text-gray-700">Poster</label>
        <div class="border shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block relative w-44 h-44 sm:text-sm border-gray-300 rounded-md mt-1 overflow-hidden bg-cover bg-no-repeat bg-center"
            :style="posterStyle">
            <div class="absolute z-10 left-0 top-0 w-full h-full flex flex-col justify-center items-center bg-opacity-50 text-gray-500 opacity-0 hover:opacity-100">
                <podlove-button class="w-32 mb-2"><span class="w-full text-center">URL</span></podlove-button>
                <podlove-button class="w-32 text-center mb-2"><span class="w-full text-center">Media</span></podlove-button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, StyleValue } from 'vue';
import { PencilIcon } from '@heroicons/vue/24/outline'
import PodloveButton from '@components/button/Button.vue'

const posterStyle = computed(() => {
    return { 'background-image': `url('https://grobschmuck.com/bilder/werktisch.jpg')` }
});

</script>
