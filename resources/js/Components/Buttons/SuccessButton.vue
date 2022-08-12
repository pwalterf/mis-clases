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
    <Button :type="type" class="text-white bg-green-700 hover:bg-green-800 focus:bg-green-900 focus:ring-green-600 active:bg-green-900">
        <slot />
    </Button>
</template>
