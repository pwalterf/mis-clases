<template>
    <AppLayout title="Lecciones">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'lessons.index'">Lecciones</BreadcrumbLink>
                </template>

                <template #static>
                    Nueva Lección
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <FormSection @submitted="submit">
                <template #form>
                    <!-- Lesson Date -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="lesson_date" value="Fecha de la Lección" />
                        <JetInput id="lesson_date" v-model="form.lesson_date" type="date" class="mt-1 block w-full" :error="form.errors.lesson_date" required />
                        <JetInputError :message="form.errors.lesson_date" class="mt-2" />
                    </div>

                    <!-- Comment -->
                    <div class="col-span-6 md:col-span-3 row-span-3 flex flex-col">
                        <JetLabel for="comment" value="Comentario" />
                        <div class="h-full mt-1">
                            <JetTextarea id="comment" v-model="form.comment" class="block w-full h-full resize-none" rows="4" :error="form.errors.comment" required />
                        </div>
                        <JetInputError :message="form.errors.comment" class="mt-2" />
                    </div>

                    <!-- Student Page -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="student_page" value="Libro Estudiante" />
                        <JetInput id="student_page" v-model="form.student_page" type="text" class="mt-1 block w-full" :error="form.errors.student_page" />
                        <JetInputError :message="form.errors.student_page" class="mt-2" />
                    </div>

                    <!-- Workbook Page -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="workbook_page" value="Libro Ejercicios" />
                        <JetInput id="workbook_page" v-model="form.workbook_page" type="text" class="mt-1 block w-full" :error="form.errors.workbook_page" />
                        <JetInputError :message="form.errors.workbook_page" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <div class="flex gap-4">
                        <JetInputError v-if="form.errors.classroom_id" :message="['Es obligatorio asignar la clase.']" class="mt-2" />
                        <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600"
                            @click="openModal"
                        >
                            <span v-if="loading" class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2" role="status" aria-label="loading"></span>
                            <span>{{ form.classroom_id ? 'Reasignar' : 'Asignar' }} Clase</span>
                        </Button>

                        <Button class="bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !form.classroom_id"
                        >
                            Guardar
                        </Button>
                    </div>
                </template>
            </FormSection>

            <div v-if="selected" class="mt-4">
                <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2">{{ selected.name }}</h2>

                <div class="relative overflow-x-auto shadow sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                            <tr>
                                <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                    Nombre
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
                            <tr v-if="selected.students.length === 0" class="bg-white">
                                <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No hay alumnos asociados a la clase.</td>
                            </tr>
                            <tr v-else v-for="(student, index) in selected.students" :key="student.id" :class="{'border-b': index != selected.students.length - 1}" class="bg-white hover:bg-gray-50">
                                <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                    {{ student.user.firstname + ' ' + student.user.lastname }}
                                </th>
                                <td class="px-2 py-4">
                                    {{ student.user.email }}
                                </td>
                                <td class="pl-2 pr-4 sm:pr-6 py-4 text-center">
                                    <Badge :class="student.deleted_at ? 'bg-red-500' : 'bg-green-500'">
                                        {{ student.deleted_at ? 'Inactivo' : 'Activo' }}
                                    </Badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Listado de Clases -->
            <JetDialogModal :show="open" @close="closeModal">
                <template #title>
                    Clases
                </template>

                <template #content>
                    <div class="relative overflow-x-auto shadow sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                                <tr>
                                    <th scope="col" class="pl-4 sm:pl-6 pr-2 py-4">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-2 py-3">
                                        Estado
                                    </th>
                                    <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                        <span class="sr-only">Asociar</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="classrooms.length === 0" class="bg-white">
                                    <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No existen clases creadas.</td>
                                </tr>
                                <tr v-else v-for="(classroom, index) in classrooms" :key="classroom.id" :class="{'border-b': index != classrooms.length - 1}" class="bg-white hover:bg-gray-50">
                                    <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ classroom.name }}
                                    </th>
                                    <td class="px-2 py-4">
                                        <Badge :class="classroom.deleted_at ? 'bg-red-500' : 'bg-green-500'">
                                            {{ classroom.deleted_at ? 'Inactiva' : 'Activa' }}
                                        </Badge>
                                    </td>
                                    <td class="pl-2 pr-4 sm:pr-6 py-4 flex justify-end">
                                        <CheckCircleIcon class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="addClassroom(classroom)" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </JetDialogModal>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import FormSection from '@/Components/FormSection.vue'
import Button from '@/Components/Button.vue'
import Badge from '@/Components/Badge.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetTextarea from '@/Jetstream/Textarea.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { CheckCircleIcon } from '@heroicons/vue/outline'

const props = defineProps({
    classroom: Object,
})

const open = ref(false)
const loading = ref(false)
const selected = ref(props.classroom)
const classroomList = ref([])
const classrooms = computed(() => {return classroomList.value.filter((classroom) => selected.value?.id !== classroom.id)})

const form = useForm({
    _method: 'POST',
    lesson_date: '',
    student_page: '',
    workbook_page: '',
    comment: '',
    classroom_id: props.classroom?.id,
})

const submit = () => {
    form.post(route('lessons.store'), {
        preserveScroll: true,
    })
}

const openModal = async () => {
    loading.value = true
    if (classrooms.value.length === 0) {
        await getClassrooms()
    }
    loading.value = false
    open.value = true
}

const closeModal = () => {
    open.value = false
}

const addClassroom = (classroom) => {
    if (form.classroom_id !== classroom.id) {
        form.classroom_id = classroom.id
        selected.value = classroom
    }

    closeModal()
}

const getClassrooms = async () => {
    let response = await axios.get('/classrooms/active-students')
    classroomList.value = response.data
}
</script>