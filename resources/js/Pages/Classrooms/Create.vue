<template>
    <AppLayout title="Clases">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'classrooms.index'">Salas de Clases</BreadcrumbLink>
                </template>

                <template #static>
                    Nueva Clase
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <FormSection @submitted="submit">
                <template #form>
                    <div class="col-span-6">
                        <div class="grid grid-cols-6 gap-x-8 gap-y-4">
                            <div class="col-span-6 md:col-span-4">
                                <!-- Name -->
                                <div>
                                    <JetLabel for="name" value="Nombre" />
                                    <JetInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" :error="form.errors.name" required />
                                    <JetInputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Description -->
                                <div class="mt-4">
                                    <JetLabel for="description" value="Descripción" />
                                    <JetInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" :error="form.errors.description" />
                                    <JetInputError :message="form.errors.description" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-6 md:col-span-2">
                                <!-- Started_at -->
                                <div>
                                    <JetLabel for="started_at" value="Fecha Suscripción" />
                                    <JetInput id="started_at" v-model="form.started_at" type="date" class="mt-1 block w-full" :error="form.errors.started_at" required />
                                    <JetInputError :message="form.errors.started_at" class="mt-2" />
                                </div>

                                <!-- Price_hr -->
                                <div class="mt-4">
                                    <JetLabel for="price_hr" value="Precio por Hora" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none z-20 pl-4">
                                            <span class="text-gray-600">$</span>
                                        </div>
                                        <JetInput id="price_hr" v-model="form.price_hr" type="text" class="mt-1 block w-full pl-10" :error="form.errors.price_hr" required />
                                    </div>
                                    <JetInputError :message="form.errors.price_hr" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2">Alumnos</h2>

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
                                        <th scope="col" class="px-2 py-3">
                                            Créditos
                                        </th>
                                        <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                            <span class="sr-only">Desasociar</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="form.students.length === 0" class="bg-white">
                                        <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No hay alumnos asociados a la clase.</td>
                                    </tr>
                                    <tr v-else v-for="(studentClass, index) in form.students" :key="studentClass.id" :class="{'border-b': index != form.students.length - 1}" class="bg-white hover:bg-gray-50">
                                        <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                            {{ studentClass.firstname + ' ' + studentClass.lastname }}
                                        </th>
                                        <td class="px-2 py-4">
                                            {{ studentClass.email }}
                                        </td>
                                        <td class="py-2">
                                            <JetInput v-model="studentClass.credit" type="text" class="py-1 w-20" :error="form.errors[`students.${index}.credit`]" />
                                            <JetInputError v-if="form.errors[`students.${index}.credit`]" :message="form.errors[`students.${index}.credit`]" class="mt-1" />
                                        </td>
                                        <td class="pl-2 pr-4 sm:pr-6 py-4">
                                            <div class="flex justify-end items-center">
                                                <UserRemoveIcon class="h-5 w-5 text-red-600 cursor-pointer" aria-hidden="true" @click="removeStudent(studentClass)" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end mt-2">
                            <SecondaryButton type="button" :class="{ 'opacity-25': loading }" :disabled="loading" @click="openModal">
                                <span v-if="loading" class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2" role="status" aria-label="loading"></span>
                                Agregar Alumno
                            </SecondaryButton>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Guardar
                    </PrimaryButton>
                </template>
            </FormSection>

            <!-- Listado de Alumnos -->
            <JetDialogModal :show="open" @close="closeModal">
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
                                    <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                        <span class="sr-only">Asociar</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="studentsList.length === 0" class="bg-white">
                                    <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No existen alumnos libres.</td>
                                </tr>
                                <tr v-else v-for="(student, index) in studentsList" :key="student.id" :class="{'border-b': index != studentsList.length - 1}" class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                        {{ student.firstname + ' ' + student.lastname }}
                                    </th>
                                    <td class="px-2 py-4">
                                        {{ student.email }}
                                    </td>
                                    <td class="pl-2 pr-4 sm:pr-6 py-4">
                                        <div class="flex justify-end items-center">
                                            <UserAddIcon class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="addStudent(student)" />
                                        </div>
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
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { UserAddIcon, UserRemoveIcon } from '@heroicons/vue/outline'

const open = ref(false)
const loading = ref(false)
const students = ref([])

const studentsList = computed(() => {
    return students.value.filter((student) => !form.students.includes(student))
})

const form = useForm({
    name: '',
    description: '',
    price_hr: '',
    started_at: '',
    students: []
})

const submit = () => {
    form.transform((data) => ({
        ...data,
        students: data.students.flatMap(student => ({['user_id']: student.id, ['credit']: student.credit}))
    }))
    .post(route('classrooms.store'), {
        preserveScroll: true,
    })
}

const getStudents = async () => {
    if (students.value.length === 0) {
        loading.value = true
        let response = await axios.get('/users/students')
        students.value = response.data
        loading.value = false
    }
}

const openModal = () => {
    getStudents()
    open.value = true
}

const closeModal = () => {
    open.value = false
}

const addStudent = (student) => {
    form.students.push(student)
}

const removeStudent = (studentClass) => {
    form.students = form.students.filter((student) => {
        return student !== studentClass
    })
}
</script>