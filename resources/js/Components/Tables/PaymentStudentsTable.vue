<template>
    <EasyDataTable
        :headers="filteredHeaders"
        :items="items"
        :rows-per-page="rows"
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

        <template #item-actions="{ id }">
            <div class="flex justify-end items-center gap-2">
                <template v-if="actions.includes('addStudent')">
                    <UserAddIcon class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="$emit('addStudent', id)" />
                </template>
            </div>
        </template>
    </EasyDataTable>
</template>

<script setup>
import { computed, ref } from 'vue'
import EasyDataTable from 'vue3-easy-data-table'
import Badge from '@/Components/Badge.vue'
import { UserAddIcon } from '@heroicons/vue/outline'

const props = defineProps({
    items: Array,
    headers: {
        type: Array,
        default: [
            'user.firstname',
            'classroom.name',
            'status',
            'actions',
        ]
    },
    actions: {
        type: Array,
        default: [
            'addStudent',
        ]
    },
    rows: {
        type: Number,
        default: 5
    },
})

const emits = defineEmits([
    'addStudent',
])

const allHeaders = ref([
    { text: "NOMBRE", value: "user.firstname", sortable: true },
    { text: "CLASE", value: "classroom.name", sortable: true },
    { text: "CRÉDITOS", value: "new_credit" },
    { text: "ESTADO", value: "status" },
    { text: "", value: "actions" },
])

const filteredHeaders = computed(() => {
    return allHeaders.value.filter(header => props.headers.includes(header.value))
})
</script>