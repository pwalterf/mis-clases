<template>
    <EasyDataTable
        :headers="headers"
        :items="items"
        :rows-per-page="5"
        hide-rows-per-page
        buttons-pagination
        table-class-name="customize-table"
        theme-color="#F3E8FF"
        empty-message="No existen alumnos para mostrar."
    >
        <template #item-firstname="{ firstname, lastname }">
            <span class="font-semibold">
                {{ firstname + ' ' + lastname }}
            </span>
        </template>

        <template #item-actions="{ id }">
            <div class="flex justify-end items-center">
                <UserAddIcon class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="$emit('addStudent', id)" />
            </div>
        </template>
    </EasyDataTable>
</template>

<script setup>
import { UserAddIcon } from '@heroicons/vue/outline'
import EasyDataTable from 'vue3-easy-data-table'

const props = defineProps({
    items: Array,
})

const emits = defineEmits([
    'addStudent',
])

const headers = [
    { text: "NOMBRE", value: "firstname", sortable: true },
    { text: "EMAIL", value: "email", sortable: true },
    { text: "", value: "actions" },
]
</script>