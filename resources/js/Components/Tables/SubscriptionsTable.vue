<template>
    <EasyDataTable
        :headers="headers"
        :items="items"
        :rows-per-page="5"
        hide-rows-per-page
        buttons-pagination
        table-class-name="customize-table"
        theme-color="#F3E8FF"
        empty-message="No existen suscripciones para mostrar."
    >
        <template #item-started_at="{ started_at }">
            <span class="font-semibold">
                {{ dateFormat.format(new Date(started_at)) }}
            </span>
        </template>

        <template #item-price_hr="{ price_hr }">
            <span class="font-semibold">
                {{ currencyFormat.format(price_hr) }}
            </span>
        </template>

        <template #item-actions="{ id }">
            <div class="flex justify-end items-center">
                <TrashIcon class="h-5 w-5 text-red-600 cursor-pointer" aria-hidden="true" @click="$emit('destroySubs', id)" />
            </div>
        </template>
    </EasyDataTable>
</template>

<script setup>
import EasyDataTable from 'vue3-easy-data-table'
import { TrashIcon } from '@heroicons/vue/outline'

const props = defineProps({
    items: Array,
})

const emits = defineEmits([
    'destroySubs',
])

const headers = [
    { text: "FECHA", value: "started_at", sortable: true },
    { text: "PRECIO / HR", value: "price_hr" },
    { text: "", value: "actions" },
]

const dateFormat = new Intl.DateTimeFormat('es-AR', {timeZone: 'UTC', day: '2-digit', month: '2-digit', year: 'numeric'})
const currencyFormat = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' })
</script>