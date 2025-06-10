<template>
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Summary
        </label>
        <div class="mt-1">
            <textarea id="description" name="description" maxlength="250" class="
          shadow-sm
          focus:ring-indigo-500 focus:border-indigo-500
          p-2
          mt-1
          block
          w-full
          sm:text-sm
          border border-gray-300
          rounded-md
          resize-y
        "
        :value="button.description"
        @input="changeSubscription($event)"
        ></textarea>
        </div>
        <p class="mt-2 text-sm text-gray-500 flex justify-between">
            <span>Elaborate description of the podcast's content.</span>
        </p>
    </div>
</template>

<script setup lang="ts">
import { injectStore } from 'redux-vuex';
import { SubscribeButton } from '../../../types/buttons.types'
import { updateItem as updateButtonItem } from '../../../store/buttons.store';

const props = defineProps<{
    button: SubscribeButton;
}>();

const store = injectStore();

const changeSubscription = (event: Event) => {
    store.dispatch(
        updateButtonItem({id: props.button.id, prop: 'subscription', value: (event.target as HTMLInputElement).value})
    )
}
</script>
