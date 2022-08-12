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
    <Button :type="type" class="text-white bg-red-700 hover:bg-red-800 focus:bg-red-900 focus:ring-red-600 active:bg-red-900">
        <slot />
    </Button>
</template>
