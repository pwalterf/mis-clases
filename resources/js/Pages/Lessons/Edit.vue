<template>
    <AppLayout title="Lecciones">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'lessons.index'">Lecciones</BreadcrumbLink>
                </template>

                <template #static>
                    Modificar Lección
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
                        <SecondaryButton type="button" @click="openModal">
                            <span v-if="loading" class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2" role="status" aria-label="loading"></span>
                            <span>{{ form.classroom_id ? 'Reasignar' : 'Asignar' }} Clase</span>
                        </SecondaryButton>
                        
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !form.classroom_id || !form.isDirty">
                            <span v-text="form.isDirty ? 'Guardar Cambios' : 'Sin Cambios'"></span>
                        </PrimaryButton>
                    </div>

                    <DangerButton type="button" :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="openConfirm">
                        Eliminar
                    </DangerButton>
                </template>
            </FormSection>

            <div v-if="selected" class="mt-4">
                <Link :href="route('classrooms.edit', selected.id)" class="hover:text-blue-800">
                    <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2 inline-flex items-center">
                        <LinkIcon class="h-5 w-5 mr-2" aria-hidden="true" />
                        {{ selected.name }}
                    </h2>
                </Link>

                <div class="relative overflow-x-auto shadow sm:rounded-md">
                    <ClassroomStudentsTable
                        :items="selected.students"
                        :headers="['user.firstname', 'user.email', 'status']"
                        :actions="[]"
                    />
                </div>
            </div>

            <!-- Listado de Clases -->
            <JetDialogModal :show="open" @close="closeModal">
                <template #title>
                    Clases
                </template>

                <template #content>
                    <ClassroomsTable
                        :items="classrooms"
                        :headers="['name', 'status', 'actions']"
                        :actions="['addClassroom']"
                        :rows="5"
                        @add-classroom="addClassroom"
                    />
                </template>
            </JetDialogModal>

            <!-- Modal de Confirmacion -->
            <JetDialogModal :show="confirming" :maxWidth="'sm'" @close="closeConfirm">
                <template #title>
                    Eliminar Lección
                </template>

                <template #content>
                    ¿Esta seguro que desea realizar esta acción?
                </template>

                <template #footer>
                    <CancelButton class="mr-4" @click="closeConfirm">
                        Cancelar
                    </CancelButton>

                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="confirmingAction">
                        Confirmar
                    </PrimaryButton>
                </template>
            </JetDialogModal>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useForm, Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import FormSection from '@/Components/FormSection.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import CancelButton from '@/Components/Buttons/CancelButton.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetTextarea from '@/Jetstream/Textarea.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import ClassroomStudentsTable from '@/Components/Tables/ClassroomStudentsTable.vue'
import ClassroomsTable from '@/Components/Tables/ClassroomsTable.vue'
import { LinkIcon } from '@heroicons/vue/outline'

const props = defineProps({
    lesson: Object,
})

const open = ref(false)
const confirming = ref(false)
const loading = ref(false)
const selected = ref(props.lesson.classroom)
const classroomList = ref([])
const classrooms = computed(() => {return classroomList.value.filter((classroom) => selected.value?.id !== classroom.id)})

const form = useForm({
    lesson_date: props.lesson.lesson_date,
    student_page: props.lesson.student_page,
    workbook_page: props.lesson.workbook_page,
    comment: props.lesson.comment,
    classroom_id: props.lesson.classroom.id,
})

const submit = () => {
    form.put(route('lessons.update', props.lesson.id), {
        preserveScroll: true,
    })
}

const destroy = () => {
    form.delete(route('lessons.destroy', props.lesson.id))
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

const addClassroom = (classroom_id) => {
    if (form.classroom_id !== classroom_id) {
        form.classroom_id = classroom_id
        selected.value = classrooms.value.find(classroom => classroom.id === classroom_id)
    }

    closeModal()
}

const getClassrooms = async () => {
    let response = await axios.get('/classrooms/active-students')
    classroomList.value = response.data
}

const openConfirm = () => {
    confirming.value = true
}

const closeConfirm = () => {
    confirming.value = false
}

const confirmingAction = () => {
    destroy()
}
</script>