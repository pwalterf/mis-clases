<template>
    <AppLayout title="Clases">
        <template #header>
            <div class="flex flex-row justify-between">
                <Breadcrumb>
                    <template #links>
                        <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    </template>

                    <template #static>
                        Salas de Clases
                    </template>
                </Breadcrumb>
                <Link :href="route('classrooms.create')">
                    <SecondaryButton type="button" class="border-none">
                        Nueva Clase
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
                                Nombre
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Creada
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Precio/hr
                            </th>
                            <th scope="col" class="px-2 py-3 text-center">
                                Estado
                            </th>
                            <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="classrooms.length === 0" class="bg-white">
                            <td colspan="5" class="pl-4 sm:pl-6 pr-2 py-4">No existen clases creadas.</td>
                        </tr>
                        <tr v-else v-for="(classroom, index) in classrooms" :key="classroom.id" :class="{'border-b': index != classrooms.length - 1}" class="bg-white hover:bg-gray-50">
                            <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                {{ classroom.name }}
                            </th>
                            <td class="px-2 py-4">
                                {{ classroom.created_at }}
                            </td>
                            <td class="px-2 py-4">
                                $ {{ classroom.price_hr }}
                            </td>
                            <td class="px-2 py-4 text-center">
                                <Badge :class="classroom.deleted_at ? 'bg-red-600' : 'bg-green-600'">
                                    {{ classroom.deleted_at ? 'Inactiva' : 'Activa' }}
                                </Badge>
                            </td>
                            <td class="pl-2 pr-4 sm:pr-6 py-3">
                                <div class="flex justify-end items-center gap-2">
                                    <span v-if="loading[index] === 'students'" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                                    <UserGroupIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="studentsOpen(classroom.id, index)" />
                                    <span v-if="loading[index] === 'lessons'" class="animate-spin inline-block w-5 h-5 border-[3px] border-yellow-600 border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                                    <BookOpenIcon v-else class="h-5 w-5 text-yellow-600 cursor-pointer" aria-hidden="true" @click="lessonsOpen(classroom.id, index)" />
                                    <Link :href="route('classrooms.edit', classroom.id)">
                                        <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alumnos por clase -->
        <JetDialogModal :show="studentsModal" @close="studentsClose">
            <template #title>
                Alumnos - {{ selected.name }}
            </template>

            <template #content>
                <div class="relative overflow-x-auto shadow sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                            <tr>
                                <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                    Alumno
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Créditos
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Estado
                                </th>
                                <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="students.length === 0" class="bg-white">
                                <td colspan="4" class="pl-4 sm:pl-6 pr-2 py-4">No existen alumnos asociados a la clase.</td>
                            </tr>
                            <tr v-else v-for="(student, index) in students" :key="student.id" :class="{'border-b': index != students.length - 1}" class="bg-white hover:bg-gray-50">
                                <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                    {{ student.user.firstname + ' ' + student.user.lastname }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ student.user.email }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ student.credit }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <Badge :class="student.deleted_at ? 'bg-red-600' : 'bg-green-600'">
                                        {{ student.deleted_at ? 'Inactivo' : 'Activo' }}
                                    </Badge>
                                </td>
                                <td class="pl-2 pr-4 sm:pr-6 py-4">
                                    <Link :href="route('payments.create', student.id)">
                                        <CashIcon class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" />
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </JetDialogModal>

        <!-- Lecciones de la clase -->
        <JetDialogModal :show="lessonsModal" @close="lessonsClose">
            <template #title>
                Lecciones - {{ selected.name }}
            </template>

            <template #content>
                <div class="relative overflow-x-auto shadow sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                            <tr>
                                <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                    Fecha
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
                                <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No existen lecciones asociadas a la clase.</td>
                            </tr>
                            <tr v-else v-for="(lesson, index) in lessons" :key="lesson.id" :class="{'border-b': index != lessons.length - 1}" class="bg-white hover:bg-gray-50">
                                <th scope="row" class="pl-4 sm:pl-6 pr-2 py-3 text-gray-900">
                                    {{ lesson.lesson_date }}
                                </th>
                                <td class="px-2 py-3">
                                    {{ lesson.comment ?? 'Ninguno' }}
                                </td>
                                <td class="pl-2 pr-4 sm:pr-6 py-3">
                                    <div class="flex justify-end items-center gap-2">
                                        <Link :href="route('lessons.edit', lesson.id)">
                                            <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="!selected.deleted_at" class="flex justify-end mt-2">
                    <Link :href="route('lessons.create', selected.id)">
                        <SecondaryButton type="button">
                            Nueva Lección
                        </SecondaryButton>
                    </Link>
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
import Badge from '@/Components/Badge.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { PencilAltIcon, UserGroupIcon, BookOpenIcon, CashIcon } from '@heroicons/vue/outline'

const props = defineProps({
    classrooms: Object,
})

const loading = ref([])
const selected = ref(null)
const students = ref([])
const lessons = ref([])
const studentsModal = ref(false)
const lessonsModal = ref(false)

// Students Modal
const studentsOpen = async (classroom_id, index) => {
    if (!students.value.length || selected.value?.id !== classroom_id) {
        loading.value[index] = 'students'
        selected.value = props.classrooms.find(item => item.id === classroom_id)
        await getStudents(classroom_id)
        loading.value[index] = false
    }
    studentsModal.value = true
}

const studentsClose = () => {
    studentsModal.value = false
}

const getStudents = async (classroom_id) => {
    let response = await axios.get(route('classrooms.students', classroom_id))
    students.value = response.data
}

// Lessons Modal
const lessonsOpen = async (classroom_id, index) => {
    if (!lessons.value.length || selected.value?.id !== classroom_id) {
        loading.value[index] = 'lessons'
        selected.value = props.classrooms.find(item => item.id === classroom_id)
        await getLessons(classroom_id)
        loading.value[index] = false
    }
    lessonsModal.value = true
}

const lessonsClose = () => {
    lessonsModal.value = false
}

const getLessons = async (classroom_id) => {
    let response = await axios.get(route('classrooms.lessons', classroom_id))
    lessons.value = response.data
}
</script>