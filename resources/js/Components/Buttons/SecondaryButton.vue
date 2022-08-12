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
    <Button :type="type" class="text-white bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600">
        <slot />
    </Button>
</template>
