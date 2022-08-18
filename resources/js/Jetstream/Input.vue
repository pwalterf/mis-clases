<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    type: {
        type: String,
        default: 'text',
    },
    modelValue: String,
    error: [String, Array],
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        :type="type"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        :class="{'border-red-500 focus:border-red-500 focus:ring-red-500': error}"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
