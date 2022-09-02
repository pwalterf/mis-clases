<template>
    <section class="flex items-center justify-between mb-2">
        <div class="lg:w-48">
            <JetSelect v-model="filterClassroomStatus" class="w-full">
                <template #options>
                    <option value="all">Todos</option>
                    <option value="Activa">Activas</option>
                    <option value="Inactiva">Inactivas</option>
                </template>
            </JetSelect>
        </div>
        <div class="lg:w-64">
            <JetInput type="search" v-model="searchClassroom" class="w-full" placeholder="Buscar..." />
        </div>
    </section>

    <div class="relative overflow-x-auto shadow sm:rounded-md">
        <EasyDataTable
            :headers="filteredHeaders"
            :items="items"
            :search-value="searchClassroom"
            :filter-options="filterClassrooms"
            :rows-per-page="rows"
            hide-rows-per-page
            buttons-pagination
            table-class-name="customize-table"
            theme-color="#F3E8FF"
            empty-message="No existen clases para mostrar."
        >
            <template #item-name="{ name }">
                <span class="font-semibold">
                    {{ name }}
                </span>
            </template>

            <template #item-price_hr="{ price_hr }">
                {{ currencyFormat.format(price_hr) }}
            </template>

            <template #item-status="{ deleted_at }">
                <Badge :class="deleted_at === 'Inactiva' ? 'bg-red-600' : 'bg-green-600'">
                    {{ deleted_at }}
                </Badge>
            </template>

            <template #item-actions="{ id }">
                <div class="flex justify-end items-center gap-2">
                    <template v-if="actions.includes('students')">
                        <span v-if="loading[id] === 'students'" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                        <UserGroupIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="$emit('studentsOpen', id)" />
                    </template>

                    <template v-if="actions.includes('lessons')">
                        <span v-if="loading[id] === 'lessons'" class="animate-spin inline-block w-5 h-5 border-[3px] border-yellow-600 border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                        <BookOpenIcon v-else class="h-5 w-5 text-yellow-600 cursor-pointer" aria-hidden="true" @click="$emit('lessonsOpen', id)" />
                    </template>

                    <template v-if="actions.includes('edit')">
                        <Link :href="route('classrooms.edit', id)">
                            <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                        </Link>
                    </template>

                    <template v-if="actions.includes('addClassroom')">
                        <CheckCircleIcon class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="$emit('addClassroom', id)" />
                    </template>
                </div>
            </template>
        </EasyDataTable>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import EasyDataTable from 'vue3-easy-data-table'
import Badge from '@/Components/Badge.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetSelect from '@/Jetstream/Select.vue'
import { UserGroupIcon, BookOpenIcon, PencilAltIcon, CheckCircleIcon } from '@heroicons/vue/outline'

const props = defineProps({
    items: Array,
    headers: {
        type: Array,
        default: [
            'name',
            'created_at',
            'price_hr',
            'status',
            'actions',
        ]
    },
    actions: {
        type: Array,
        default: [
            'students',
            'lessons',
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
    'lessonsOpen',
    'addClassroom',
])

const allHeaders = ref([
  { text: "NOMBRE", value: "name", sortable: true },
  { text: "CREADA", value: "created_at", sortable: true },
  { text: "PRECIO/HR", value: "price_hr", sortable: true },
  { text: "ESTADO", value: "status" },
  { text: "", value: "actions" },
])

const filteredHeaders = computed(() => {
    return allHeaders.value.filter(header => props.headers.includes(header.value))
})

const searchClassroom = ref()
const filterClassroomStatus = ref('Activa')

const filterClassrooms = computed(() => {
    if (filterClassroomStatus.value !== 'all') {
        return [{
            field: 'deleted_at',
            comparison: '=',
            criteria: filterClassroomStatus.value,
        }]
    }
})

const currencyFormat = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' })
</script>