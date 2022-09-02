<template>
    <EasyDataTable
        :headers="filteredHeaders"
        :items="items"
        :rows-per-page="rows"
        hide-rows-per-page
        buttons-pagination
        table-class-name="customize-table"
        theme-color="#F3E8FF"
        empty-message="No existen lecciones para mostrar."
    >
        <template #item-lesson_date="{ lesson_date }">
            <span class="font-semibold">
                {{ lesson_date }}
            </span>
        </template>

        <template #item-actions="{ id, classroom }">
            <div class="flex justify-end items-center gap-2">
                <template v-if="actions.includes('students')">
                    <span v-if="loading === id" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                    <UserGroupIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="$emit('studentsOpen', classroom.id, id)" />
                </template>

                <template v-if="actions.includes('edit')">
                    <Link :href="route('lessons.edit', id)">
                        <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                    </Link>
                </template>
            </div>
        </template>
    </EasyDataTable>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3'
import EasyDataTable from 'vue3-easy-data-table'
import { PencilAltIcon, UserGroupIcon } from '@heroicons/vue/outline'

const props = defineProps({
    items: Array,
    headers: {
        type: Array,
        default: [
            'lesson_date',
            'comment',
            'student_page',
            'worknook_page',
            'actions',
        ]
    },
    actions: {
        type: Array,
        default: [
            'edit',
        ]
    },
    rows: {
        type: Number,
        default: 5
    },
    loading: Number,
})

const emits = defineEmits([
    'studentsOpen',
])

const allHeaders = ref([
    { text: "FECHA", value: "lesson_date", sortable: true },
    { text: "CLASE", value: "classroom.name", sortable: true },
    { text: "COMENTARIO", value: "comment" },
    { text: "LIBRO ESTUDIANTE", value: "student_page" },
    { text: "LIBRO EJERCICIO", value: "workbook_page" },
    { text: "", value: "actions" },
])

const filteredHeaders = computed(() => {
    return allHeaders.value.filter(header => props.headers.includes(header.value))
})

</script>