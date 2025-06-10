<template>
    <div>
        <label for="subtitle" class="block text-sm font-medium text-gray-700">Title
        </label>
        <div class="mt-1">
            <input id="subtitle" name="subtitle" maxlength="250" class="
          shadow-sm
          focus:ring-indigo-500 focus:border-indigo-500
          p-2
          block
          w-full
          sm:text-sm
          border border-gray-300
          rounded-md
          resize-y
        "
        :value="button.title"
        @input="changeTitle($event)"
        ></input>
        </div>
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

const changeTitle = (event: Event) => {
    store.dispatch(
        updateButtonItem({id: props.button.id, prop: 'title', value: (event.target as HTMLInputElement).value})
    )
}

</script>
