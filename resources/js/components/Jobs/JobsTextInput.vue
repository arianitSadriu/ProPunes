<script setup>
/**
 * JobsTextInput Component
 * A reusable text input component for job-related forms.
 * Provides styling consistent with the application's design system.
 * Supports v-model binding for two-way data binding.
 * Includes autofocus capability when the autofocus attribute is present.
 */
import { onMounted, ref } from 'vue';

/**
 * Two-way data binding model using Vue 3's defineModel
 * Allows parent components to use v-model to bind to this input's value
 * @type {String} - The input text value
 */
const model = defineModel({
    type: String,
    required: true,
});

/**
 * Template reference to the input element
 * Used for direct DOM manipulation like focusing
 */
const input = ref(null);

/**
 * Component lifecycle hook
 * Runs after the component is mounted to the DOM
 * Checks if the input has an autofocus attribute and focuses it if present
 */
onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

/**
 * Expose a focus method that parent components can call
 * Allows programmatic focus control from parent components
 * Example: templateRef.value.focus()
 */
defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <!-- Styled text input with v-model binding for two-way data flow -->
    <input
        class="rounded-lg text-sm text-gray-900 bg-gray-50 border-gray-300 border p-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
    />
</template>
