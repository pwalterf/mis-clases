<template>
    <AppLayout title="Lecciones">
        <template #header>
            <div class="flex flex-row justify-between">
                <Breadcrumb>
                    <template #links>
                        <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    </template>

                    <template #static>
                        Lecciones
                    </template>
                </Breadcrumb>
                <Link :href="route('lessons.create')">
                    <SecondaryButton type="button" class="border-none">
                        Nueva Lección
                    </SecondaryButton>
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                        <tr>
                            <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                Fecha
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Clase
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Comentario
                            </th>
                            <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="lessons.length === 0" class="bg-white">
                            <td colspan="4" class="pl-4 sm:pl-6 pr-2 py-3">No existen clases lecciones creadas.</td>
                        </tr>
                        <tr v-else v-for="(lesson, index) in lessons" :key="lesson.id" :class="{'border-b': index != lessons.length - 1}" class="bg-white hover:bg-gray-50">
                            <td class="pl-4 sm:pl-6 pr-2 py-3">
                                {{ lesson.lesson_date }}
                            </td>
                            <td class="px-2 py-4">
                                {{ lesson.classroom.name }}
                            </td>
                            <td class="px-2 py-4">
                                {{ lesson.comment ?? 'Ninguno' }}
                            </td>
                            <td class="pl-2 pr-4 sm:pr-6 py-3">
                                <div class="flex justify-end items-center gap-2">
                                    <span v-if="loading[index]" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                                    <UserGroupIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="studentsOpen(lesson.classroom.id, index)" />
                                    <Link :href="route('lessons.edit', lesson.id)">
                                        <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alumnos en la leccion -->
        <JetDialogModal :show="studentsModal" @close="studentsClose">
            <template #title>
                Alumnos
            </template>

            <template #content>
                <div class="relative overflow-x-auto shadow sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                            <tr>
                                <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                    Alumno
                                </th>
                                <th scope="col" class="px-2 py-3">
                                    Email
                                </th>
                                <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3 text-center">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="students.length === 0" class="bg-white">
                                <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No existen alumnos asociados a la clase.</td>
                            </tr>
                            <tr v-else v-for="(student, index) in students" :key="student.id" :class="{'border-b': index != students.length - 1}" class="bg-white hover:bg-gray-50">
                                <td scope="row" class="pl-4 sm:pl-6 pr-2 py-4">
                                    {{ student.user.firstname + ' ' + student.user.lastname }}
                                </td>
                                <td class="px-2 py-4">
                                    {{ student.user.email }}
                                </td>
                                <td class="pl-2 pr-4 sm:pr-6 py-4 text-center">
                                    <Badge :class="student.deleted_at ? 'bg-red-600' : 'bg-green-600'">
                                        {{ student.deleted_at ? 'Inactivo' : 'Activo' }}
                                    </Badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </JetDialogModal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Badge from '@/Components/Badge.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { PencilAltIcon, UserGroupIcon } from '@heroicons/vue/outline'

const props = defineProps({
    lessons: Object,
})

const loading = ref([])
const selected = ref(null)
const students = ref([])
const studentsModal = ref(false)

const studentsOpen = async (classroom, index) => {
    if (selected.value !== classroom) {
        loading.value[index] = true
        selected.value = classroom
        await getStudents(classroom)
        loading.value[index] = false
    }
    studentsModal.value = true
}

const studentsClose = () => {
    studentsModal.value = false
}

const getStudents = async (classroom) => {
    let response = await axios.get('/classrooms/' + classroom + '/students')
    students.value = response.data
}
</script>