<template>
    <li class="font-semibold text-lg md:text-xl leading-tight flex items-center">
        <Link class="text-purple-400 hover:text-purple-600" :href="route(href)">
            <slot />
        </Link>
        <svg class="flex-shrink-0 mx-1 md:mx-3 overflow-visible h-2.5 w-2.5 text-gray-400" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </li>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    href: String
})
</script>