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
    <Button :type="type" class="bg-white hover:bg-gray-100 focus:bg-gray-300 focus:ring-white active:bg-gray-300">
        <slot />
    </Button>
</template>
