<template>
    <EasyDataTable
        :headers="filteredHeaders"
        :items="filteredItems"
        :rows-per-page="5"
        hide-rows-per-page
        buttons-pagination
        table-class-name="customize-table"
        theme-color="#F3E8FF"
        empty-message="No existen alumnos para mostrar."
    >
        <template #item-user.firstname="{ user }">
            <span class="font-semibold">
                {{ user.firstname + ' ' + user.lastname }}
            </span>
        </template>

        <template #item-status="{ deleted_at }">
            <Badge :class="deleted_at === 'Inactivo' ? 'bg-red-600' : 'bg-green-600'">
                {{ deleted_at }}
            </Badge>
        </template>

        <template #item-actions="{ id, deleted_at }">
            <div class="flex justify-end items-center gap-2">
                <template v-if="actions.includes('payments')">
                    <span v-if="loading[id]" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                    <CashIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="$emit('paymentsOpenModal', id)" />
                </template>

                <template v-if="actions.includes('newPayment')">
                    <Link :href="route('payments.create', id)">
                        <CashIcon class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" />
                    </Link>
                </template>

                <template v-if="actions.includes('edit')">
                    <PencilAltIcon class="h-5 w-5 text-blue-600 cursor-pointer" aria-hidden="true" @click="$emit('studentOpenModal', id)" />
                </template>

                <template v-if="actions.includes('toggle') && !items.deleted_at">
                    <TrashIcon v-if="deleted_at === 'Activo'" class="h-5 w-5 text-red-600 cursor-pointer" aria-hidden="true" @click="$emit('removeStudent', id)" />
                    <ClipboardCheckIcon v-else class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="$emit('restoreStudent', id)" />
                </template>
            </div>
        </template>
    </EasyDataTable>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3'
import EasyDataTable from 'vue3-easy-data-table'
import Badge from '@/Components/Badge.vue'
import { CashIcon, PencilAltIcon, TrashIcon, ClipboardCheckIcon } from '@heroicons/vue/outline'

const props = defineProps({
    items: [Object, Array],
    headers: {
        type: Array,
        default: [
            'user.firstname',
            'user.email',
            'credit',
            'status',
            'actions',
        ]
    },
    actions: {
        type: Array,
        default: ['payments', 'edit', 'toggle'],
    },
    loading: Array,
})

const emits = defineEmits([
    'paymentsOpenModal',
    'studentOpenModal',
    'removeStudent',
    'restoreStudent',
])

const allHeaders = ref([
    { text: "NOMBRE", value: "user.firstname", sortable: true },
    { text: "EMAIL", value: "user.email", sortable: true },
    { text: "CRÉDITOS", value: "credit" },
    { text: "ESTADO", value: "status" },
    { text: "", value: "actions" },
])

const filteredHeaders = computed(() => {
    return allHeaders.value.filter(header => props.headers.includes(header.value))
})

const filteredItems = computed(() => {
    if (Array.isArray(props.items)) return props.items
    return props.items.students
})
</script>