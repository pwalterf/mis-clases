<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? 'flex p-2 gap-3 rounded border-l-4 border-purple-300 text-purple-800 bg-purple-100 focus:text-purple-900 focus:bg-purple-200 focus:border-purple-700 transition'
        : 'flex p-2 gap-3 rounded border-l-4 border-transparent text-gray-500 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:text-gray-900 focus:bg-gray-200 focus:border-gray-500 transition';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-left">
            <slot />
        </button>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
