<script setup>
import Button from '@/Components/Buttons/Button.vue'

defineProps({
    type: {
        type: String,
        default: 'submit',
    },
})
</script>

<template>
    <Button :type="type" class="text-white bg-gray-700 hover:bg-gray-800 focus:bg-gray-900 focus:ring-gray-600 active:bg-gray-900">
        <slot />
    </Button>
</template>
