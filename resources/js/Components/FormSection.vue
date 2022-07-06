<script setup>
import { computed, useSlots } from 'vue';

defineEmits(['submitted']);

const hasActions = computed(() => !! useSlots().actions);
</script>

<template>
    <form @submit.prevent="$emit('submitted')" novalidate>
        <div
            class="px-4 py-5 bg-white sm:p-6 shadow"
            :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'"
        >
            <div class="grid grid-cols-6 gap-6">
                <slot name="form" />
            </div>
        </div>

        <div v-if="hasActions" class="flex flex-row-reverse items-center justify-between px-4 py-2 bg-purple-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <slot name="actions" />
        </div>
    </form>
</template>
