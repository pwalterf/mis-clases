<template>
    <Listbox
        :model-value="modelValue"
        :multiple="multiple"
        @update:model-value="value => emit('update:modelValue', value)">
        <div class="relative mt-1">
            <ListboxButton
                class="border mt-1 block w-full cursor-default py-2 pl-3 pr-10 text-left border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                :class="{'border-red-500 focus:border-red-500 focus:ring-red-500': error}">
                <span v-if="label.length" class="block truncate capitalize space-x-2">
                    <Badge v-for="l in label" :key="l" class="bg-gray-400">
                        {{ l }}
                    </Badge>
                </span>
                <span v-else class="text-gray-500">{{ placeholder }}</span>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <SelectorIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                </span>
            </ListboxButton>

            <transition
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform opacity-100"
                leave-to-class="transform opacity-0"
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-y-75 -translate-y-5"
                enter-to-class="transform scale-y-100 translate-y-0">
                <ListboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                    <ListboxOption
                        v-for="option in options"
                        :key="option.id"
                        :value="option.id"
                        v-slot="{ active, selected }"
                        as="template"
                    >
                        <li :class="[active ? 'bg-indigo-100 text-indigo-900' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-10 pr-4']">
                            <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate capitalize']">
                                {{ option.name }}
                            </span>
                            <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>

<script setup>
import { computed } from 'vue';
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import { CheckIcon, SelectorIcon } from '@heroicons/vue/solid'
import Badge from '@/Components/Badge.vue';

const props = defineProps({
    options: Array,
    modelValue: [String, Number, Array],
    placeholder: {
        type: String,
        default: 'Seleccione una opción',
    },
    multiple: Boolean,
    error: [String, Array],
})

const emit = defineEmits(['update:modelValue'])

const label = computed(() => {
    return props.options.filter(option => {
        if (Array.isArray(props.modelValue)) {
            return props.modelValue.includes(option.id)
        }

        return option.id === props.modelValue
    }).map(option => option.name)
})
</script>