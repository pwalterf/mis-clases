<template>
    <section class="flex items-center justify-between mb-2">
        <div class="lg:w-48">
            <JetSelect v-model="filterStatus" class="w-full">
                <template #options>
                    <option value="all">Todos</option>
                    <option value="Activo">Activos</option>
                    <option value="Inactivo">Inactivos</option>
                </template>
            </JetSelect>
        </div>
        <div class="lg:w-64">
            <JetInput type="search" v-model="searchValue" class="w-full" placeholder="Buscar..." />
        </div>
    </section>
    
    <div class="relative overflow-auto shadow-md sm:rounded-md">
        <EasyDataTable
            :headers="headers"
            :items="items"
            :search-value="searchValue"
            :filter-options="filterOptions"
            :rows-per-page="10"
            hide-rows-per-page
            buttons-pagination
            table-class-name="customize-table"
            theme-color="#F3E8FF"
            empty-message="No existen usuarios para mostrar."
        >
            <template #item-firstname="{ firstname, lastname }">
                <span class="font-semibold">
                    {{ firstname + ' ' + lastname }}
                </span>
            </template>

            <template #item-role="{ roles }">
                <span class="space-x-2 whitespace-nowrap">
                    <Badge v-for="role in roles" :key="role" class="bg-gray-400">
                        {{ role }}
                    </Badge>
                </span>
            </template>

            <template #item-status="{ deleted_at }">
                <Badge :class="deleted_at === 'Inactivo' ? 'bg-red-600' : 'bg-green-600'">
                    {{ deleted_at }}
                </Badge>
            </template>

            <template #item-actions="{ id }">
                <div class="flex justify-end items-center">
                    <Link :href="route('users.edit', id)" class="p-1">
                        <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                    </Link>
                </div>
            </template>
        </EasyDataTable>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import EasyDataTable from 'vue3-easy-data-table'
import JetInput from '@/Jetstream/Input.vue'
import JetSelect from '@/Jetstream/Select.vue'
import Badge from '@/Components/Badge.vue'
import { PencilAltIcon } from '@heroicons/vue/outline'

const props = defineProps({
    items: Array,
})

const headers = [
  { text: "NOMBRE", value: "firstname", sortable: true },
  { text: "EMAIL", value: "email", sortable: true },
  { text: "CREADO", value: "created_at", sortable: true },
  { text: "ROL", value: "role" },
  { text: "ESTADO", value: "status" },
  { text: "", value: "actions" },
]

const searchValue = ref()
const filterStatus = ref('Activo')

const filterOptions = computed(() => {
    if (filterStatus.value !== 'all') {
        return [{
            field: 'deleted_at',
            comparison: '=',
            criteria: filterStatus.value,
        }]
    }
})
</script>