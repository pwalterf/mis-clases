<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
})
</script>

<template>
    <button :type="type" class="relative z-0 inline-flex items-center px-3 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition-all">
        <slot />
    </button>
</template>
