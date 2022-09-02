<template>
    <AppLayout title="Clases">
        <template #header>
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
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ClassroomsTable
                :items="classrooms"
                :loading="loading"
                @students-open="studentsOpen"
                @lessons-open="lessonsOpen"
            />
        </div>

        <!-- Alumnos por clase -->
        <JetDialogModal :show="studentsModal" @close="studentsClose">
            <template #title>
                Alumnos - {{ selected.name }}
            </template>

            <template #content>
                <div class="relative overflow-x-auto shadow sm:rounded-md">
                    <ClassroomStudentsTable
                        :items="selected"
                        :actions="['newPayment']"
                        :loading="loading"
                    />
                </div>
            </template>
        </JetDialogModal>

        <!-- Lecciones de la clase -->
        <JetDialogModal :show="lessonsModal" @close="lessonsClose">
            <template #title>
                Lecciones - {{ selected.name }}
            </template>

            <template #content>
                <div class="relative overflow-x-auto shadow sm:rounded-md">
                    <LessonsTable
                        :items="lessons"
                    />
                </div>

                <div v-if="selected.deleted_at === 'Activa'" class="flex justify-end mt-2">
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
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import ClassroomsTable from '@/Components/Tables/ClassroomsTable.vue'
import ClassroomStudentsTable from '@/Components/Tables/ClassroomStudentsTable.vue'
import LessonsTable from '@/Components/Tables/LessonsTable.vue'

const props = defineProps({
    classrooms: Object,
})

const loading = ref([])
const selected = ref(null)
const lessons = ref([])
const studentsModal = ref(false)
const lessonsModal = ref(false)

// Students Modal
const studentsOpen = async (classroom_id) => {
    if (!selected.value?.students.length || selected.value?.id !== classroom_id) {
        loading.value[classroom_id] = 'students'
        selected.value = props.classrooms.find(item => item.id === classroom_id)
        await getStudents(classroom_id)
        loading.value[classroom_id] = false
    }
    studentsModal.value = true
}

const studentsClose = () => {
    studentsModal.value = false
}

const getStudents = async (classroom_id) => {
    let response = await axios.get(route('classrooms.students', classroom_id))
    selected.value.students = response.data
}

// Lessons Modal
const lessonsOpen = async (classroom_id) => {
    if (!lessons.value.length || selected.value?.id !== classroom_id) {
        loading.value[classroom_id] = 'lessons'
        selected.value = props.classrooms.find(item => item.id === classroom_id)
        await getLessons(classroom_id)
        loading.value[classroom_id] = false
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