<template>
    <AppLayout title="Lecciones">
        <template #header>
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
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow sm:rounded-md">
                <LessonsTable
                    :items="lessons"
                    :headers="['lesson_date', 'classroom.name', 'comment', 'actions']"
                    :actions="['students', 'edit']"
                    :rows="10"
                    :loading="loading"
                    @students-open="studentsOpen"
                />
            </div>
        </div>

        <!-- Alumnos en la leccion -->
        <JetDialogModal :show="studentsModal" @close="studentsClose">
            <template #title>
                Alumnos
            </template>

            <template #content>
                <div class="relative overflow-x-auto shadow sm:rounded-md">
                    <ClassroomStudentsTable
                        :items="students"
                        :headers="['user.firstname', 'user.email', 'status']"
                        :actions="[]"
                    />
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
import LessonsTable from '@/Components/Tables/LessonsTable.vue'
import ClassroomStudentsTable from '@/Components/Tables/ClassroomStudentsTable.vue'

const props = defineProps({
    lessons: Object,
})

const loading = ref(null)
const selected = ref(null)
const students = ref([])
const studentsModal = ref(false)

const studentsOpen = async (classroom_id, lesson_id) => {
    if (selected.value !== classroom_id) {
        loading.value = lesson_id
        selected.value = classroom_id
        await getStudents(classroom_id)
        loading.value = null
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
</script>