<template>
    <EasyDataTable
        :headers="headers"
        :items="items"
        :rows-per-page="rows"
        hide-rows-per-page
        buttons-pagination
        table-class-name="customize-table"
        theme-color="#F3E8FF"
        empty-message="No existen pagos para mostrar."
    >
        <template #item-payment_date="{ payment_date }">
            <span class="font-semibold">
                {{ payment_date }}
            </span>
        </template>

        <template #item-income="{ income }">
            {{ currencyFormat.format(income) }}
        </template>

        <template #item-actions="{ id }">
            <div class="flex justify-end items-center gap-2">
                <template v-if="actions.includes('students')">
                    <span v-if="loading[id]" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                    <UserGroupIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="$emit('studentsOpen', id)" />
                </template>

                <template v-if="actions.includes('edit')">
                    <Link :href="route('payments.edit', id)">
                        <PencilAltIcon class="h-5 w-5 text-blue-600 cursor-pointer" aria-hidden="true"  />
                    </Link>
                </template>
            </div>
        </template>
    </EasyDataTable>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import EasyDataTable from 'vue3-easy-data-table'
import { PencilAltIcon, UserGroupIcon } from '@heroicons/vue/outline'

const props = defineProps({
    items: Array,
    actions: {
        type: Array,
        default: [
            'students',
            'edit',
        ]
    },
    rows: {
        type: Number,
        default: 10
    },
    loading: Array,
})

const emits = defineEmits([
    'studentsOpen',
])

const headers = [
    { text: "FECHA", value: "payment_date", sortable: true },
    { text: "IMPORTE", value: "income", sortable: true },
    { text: "COMENTARIO", value: "comment" },
    { text: "", value: "actions" },
]

const currencyFormat = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' })
</script>