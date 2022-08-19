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
        ? 'flex mx-3 px-5 py-2.5 rounded border-l-4 border-purple-300 text-purple-800 font-bold bg-purple-100 focus:outline-none transition'
        : 'flex mx-3 px-5 py-2.5 rounded border-l-4 border-transparent text-gray-500 hover:text-purple-600 focus:outline-none transition';
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
