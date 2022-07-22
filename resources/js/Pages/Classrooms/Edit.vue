<template>
    <AppLayout title="Clases">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'classrooms.index'">Salas de Clases</BreadcrumbLink>
                </template>

                <template #static>
                    Modificar Clase
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-6 gap-4">
            <div class="col-span-6 md:col-span-4">
                <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2">Clase</h2>

                <FormSection @submitted="submit">
                    <template #form>
                        <!-- Name -->
                        <div class="col-span-6">
                            <JetLabel for="name" value="Nombre" />
                            <JetInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" :error="form.errors.name" required />
                            <JetInputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="col-span-6">
                            <JetLabel for="description" value="Descripción" />
                            <JetInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" :error="form.errors.description" required />
                            <JetInputError :message="form.errors.description" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <Button class="bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing || !form.isDirty"
                        >
                            <span v-text="form.isDirty ? 'Guardar Cambios' : 'Sin Cambios'"></span>
                        </Button>

                        <Button v-if="classroom.deleted_at" type="button" class="bg-green-700 hover:bg-green-800 focus:bg-green-900 focus:ring-green-600 active:bg-green-900"
                            :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal"
                        >
                            Restaurar
                        </Button>
                        <Button v-else type="button" class="bg-red-600 hover:bg-red-700 focus:bg-red-800 focus:ring-red-500 active:bg-red-800"
                            :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal"
                        >
                            Eliminar
                        </Button>
                    </template>
                </FormSection>
            </div>

            <div class="col-span-6 md:col-span-2">
                <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2">Suscripción</h2>

                <FormSection>
                    <template #form>
                        <!-- Created_at -->
                        <div class="col-span-6">
                            <JetLabel for="sub_created_at" value="Creado" />
                            <JetInput id="sub_created_at" v-model="sub_created_at" type="text" class="mt-1 block w-full bg-slate-50 text-slate-500" disabled />
                        </div>

                        <!-- Price_hr -->
                        <div class="col-span-6">
                            <JetLabel for="price_hr" value="Precio por Hora" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none z-20 pl-4">
                                    <span class="text-gray-600">$</span>
                                </div>
                                <JetInput id="price_hr" v-model="price_hr" type="text" class="mt-1 block w-full pl-10 bg-slate-50 text-slate-500" disabled />
                            </div>
                        </div>
                    </template>

                    <template #actions>
                        <Button type="button" class="bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                            :class="{ 'opacity-25': subsForm.processing}" :disabled="subsForm.processing" @click="subsOpenModal"
                        >
                            Modificar
                        </Button>

                        <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600" @click="openModal">
                            Historial
                        </Button>
                    </template>
                </FormSection>
            </div>

            <div class="col-span-6 mt-2">

                <TabGroup>
                    <div class="mb-3 border-b-2 border-gray-200">
                        <TabList class="flex -mb-px text-lg font-medium text-center space-x-6">
                            <Tab v-slot="{ selected }" as="div" class="outline-none">
                                <button type="button" class="inline-flex items-center p-4 border-b-2 gap-2" :class="selected ? 'text-purple-600 border-purple-500' : 'border-transparent text-gray-500 hover:text-purple-600'">
                                    <UserIcon class="h-5 w-5" aria-hidden="true" />
                                    Alumnos
                                </button>
                            </Tab>
                            <Tab v-slot="{ selected }" as="div" class="outline-none">
                                <button type="button" class="inline-flex items-center p-4 border-b-2 gap-2" :class="selected ? 'text-purple-600 border-purple-500' : 'border-transparent text-gray-500 hover:text-purple-600'" @click="getLessons">
                                    <span v-if="lessonsLoading" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                                    <BookOpenIcon v-else class="h-5 w-5" aria-hidden="true" />
                                    Lecciones
                                </button>
                            </Tab>
                        </TabList>
                    </div>
                    <TabPanels>
                        <!-- Alumnos -->
                        <TabPanel class="bg-white rounded-md px-6 py-4 shadow">
                            <div class="relative overflow-x-auto shadow rounded-md">
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
                                            <th scope="col" class="px-2 py-3 text-center">
                                                Estado
                                            </th>
                                            <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                                <span class="sr-only">Editar</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="classroom.students.length === 0" class="bg-white">
                                            <td colspan="5" class="pl-4 sm:pl-6 pr-2 py-4">No hay alumnos asociados a la clase.</td>
                                        </tr>
                                        <tr v-else v-for="(student, index) in classroom.students" :key="student.id" :class="{'border-b': index != classroom.students.length - 1}" class="bg-white hover:bg-gray-50">
                                            <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                                {{ student.user.firstname + ' ' + student.user.lastname }}
                                            </th>
                                            <td class="px-2 py-4">
                                                {{ student.user.email }}
                                            </td>
                                            <td class="px-2 py-4">
                                                {{ student.credit }}
                                            </td>
                                            <td class="px-2 py-4 text-center">
                                                <Badge :class="student.deleted_at ? 'bg-red-500' : 'bg-green-500'">
                                                    {{ student.deleted_at ? 'Inactivo' : 'Activo' }}
                                                </Badge>
                                            </td>
                                            <td class="pl-2 pr-4 sm:pr-6 py-4">
                                                <div class="flex justify-end items-center gap-2">
                                                    <span v-if="loading[index]" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                                                    <CashIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="paymentsOpenModal(student.id, index)" />
                                                    <Link :href="route('users.edit', student.user.id)">
                                                        <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                                                    </Link>
                                                    <template v-if="!classroom.deleted_at">
                                                        <TrashIcon v-if="!student.deleted_at" class="h-5 w-5 text-red-600 cursor-pointer" aria-hidden="true" @click="destroyStudent(student.id)" />
                                                        <ClipboardCheckIcon v-else class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="restoreStudent(student.id)" />
                                                    </template>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-end mt-2">
                                <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600"
                                    :class="{ 'opacity-25': form.processing}" :disabled="form.processing || classroom.deleted_at" @click="studentsOpenModal">
                                    Agregar Alumno
                                </Button>
                            </div>
                        </TabPanel>

                        <!-- Lecciones -->
                        <TabPanel class="bg-white rounded-md px-6 py-4 shadow">
                            <div class="relative overflow-x-auto shadow rounded-md">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                                        <tr>
                                            <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                                Fecha
                                            </th>
                                            <th scope="col" class="px-2 py-3">
                                                Comentario
                                            </th>
                                            <th scope="col" class="px-2 py-3">
                                                Libro Estudiante
                                            </th>
                                            <th scope="col" class="px-2 py-3">
                                                Libro Ejercicio
                                            </th>
                                            <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                                <span class="sr-only">Editar</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="lessons.length === 0" class="bg-white">
                                            <td colspan="4" class="pl-4 sm:pl-6 pr-2 py-3">
                                                <span v-if="lessonsLoading">Cargando datos...</span>
                                                <span v-else>La clase no posee lecciones cargadas.</span>
                                            </td>
                                        </tr>
                                        <tr v-else v-for="(lesson, index) in lessons" :key="lesson.id" :class="{'border-b': index != lessons.length - 1}" class="bg-white hover:bg-gray-50">
                                            <td class="pl-4 sm:pl-6 pr-2 py-3">
                                                {{ lesson.lesson_date }}
                                            </td>
                                            <td class="px-2 py-4">
                                                {{ lesson.comment ?? 'Ninguno' }}
                                            </td>
                                            <td class="px-2 py-4">
                                                {{ lesson.student_page ?? '-' }}
                                            </td>
                                            <td class="px-2 py-4">
                                                {{ lesson.workbook_page ?? '-' }}
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

                            <div class="flex justify-end mt-2">
                                <Link :href="route('lessons.create', classroom.id)">
                                    <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600">
                                        Nueva Lección
                                    </Button>
                                </Link>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>

            <!-- Modal de Confirmacion -->
            <JetDialogModal :show="confirming" @close="closeConfirm">
                <template #title>
                    <span v-text="classroom.deleted_at ? 'Restaurar' : 'Eliminar'"></span> Clase
                </template>

                <template #content>
                    ¿Esta seguro que desea realizar esta acción?
                </template>

                <template #footer>
                    <Button class="bg-gray-500 hover:bg-gray-600 focus:bg-gray-700 focus:ring-gray-400 active:bg-gray-700" @click="closeConfirm">
                        Cancelar
                    </Button>

                    <Button class="ml-4 bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                        :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmingAction"
                    >
                        Confirmar
                    </Button>
                </template>
            </JetDialogModal>

            <!-- Historial de Suscripciones -->
            <JetDialogModal :show="open" @close="closeModal">
                <template #title>
                    Suscripciones
                </template>

                <template #content>
                    <div class="relative overflow-x-auto shadow sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                                <tr>
                                    <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                        Precio / hr
                                    </th>
                                    <th scope="col" class="px-2 py-3">
                                        Creada
                                    </th>
                                    <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                        <span class="sr-only">Editar</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="classroom.subscriptions.length === 0" class="bg-white">
                                    <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No existen clases creadas</td>
                                </tr>
                                <tr v-else v-for="(subscription, index) in classroom.subscriptions" :key="subscription.id" :class="{'border-b': index != classroom.subscriptions.length - 1}" class="bg-white hover:bg-gray-50">
                                    <td scope="row" class="pl-4 sm:pl-6 pr-2 py-4">
                                        $ {{ subscription.price_hr }}
                                    </td>
                                    <td class="px-2 py-4">
                                        {{ subscription.created_at }}
                                    </td>
                                    <td class="pl-2 pr-4 sm:pr-6 py-4">
                                        <div class="flex justify-end items-center">
                                            <TrashIcon class="h-5 w-5 text-red-600 cursor-pointer" aria-hidden="true" @click="destroySubs(subscription.id)" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </JetDialogModal>

            <!-- Nueva Suscripcion -->
            <JetDialogModal :show="subsOpen" :maxWidth="'sm'" @close="subsCloseModal">
                <template #title>
                    Nueva Suscripción
                </template>

                <template #content>
                    <form @submit.prevent="subsSubmit" novalidate>
                        <!-- Price_hr -->
                        <div class="col-span-6">
                            <JetLabel for="price_hr" value="Precio por Hora" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none z-20 pl-4">
                                    <span class="text-gray-600">$</span>
                                </div>
                                <JetInput id="price_hr" v-model="subsForm.price_hr" type="text" class="mt-1 block w-full pl-10" :error="subsForm.errors.price_hr" required />
                            </div>
                            <JetInputError :message="subsForm.errors.price_hr" class="mt-2" />
                        </div>
                    </form>
                </template>

                <template #footer>
                    <Button class="bg-gray-500 hover:bg-gray-600 focus:bg-gray-700 focus:ring-gray-400 active:bg-gray-700" @click="subsCloseModal">
                        Cancelar
                    </Button>

                    <Button @click="subsSubmit" class="ml-4 bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700" :class="{ 'opacity-25': subsForm.processing }" :disabled="subsForm.processing">
                        Confirmar
                    </Button>
                </template>
            </JetDialogModal>

            <!-- Listado de Alumnos -->
            <JetDialogModal :show="studentsOpen" @close="studentsCloseModal">
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
                                <tr v-else v-for="(student, index) in studentsList" :key="student.id" :class="{'border-b': index != studentsList.length - 1}" class="bg-white hover:bg-gray-50">
                                    <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                        {{ student.firstname + ' ' + student.lastname }}
                                    </th>
                                    <td class="px-2 py-4">
                                        {{ student.email }}
                                    </td>
                                    <td class="pl-2 pr-4 sm:pr-6 py-4">
                                        <div class="flex justify-end items-center">
                                            <UserAddIcon class="h-5 w-5 text-green-600 cursor-pointer" aria-hidden="true" @click="addStudent(student.id)" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </JetDialogModal>

            <!-- Historial de Pagos -->
            <JetDialogModal :show="paymentsOpen != false" @close="paymentsCloseModal">
                <template #title>
                    Pagos
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
                                        Importe
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
                                <tr v-if="payments.length === 0" class="bg-white">
                                    <td colspan="4" class="pl-4 sm:pl-6 pr-2 py-4">No existen pagos realizados.</td>
                                </tr>
                                <tr v-else v-for="(payment, index) in payments" :key="payment.id" :class="{'border-b': index != payments.length - 1}" class="bg-white hover:bg-gray-50">
                                    <td scope="row" class="pl-4 sm:pl-6 pr-2 py-4">
                                        {{ payment.payment_date }}
                                    </td>
                                    <td class="px-2 py-4">
                                        {{ payment.income }}
                                    </td>
                                     <td class="px-2 py-4">
                                        {{ payment.comment ?? 'Ninguno' }}
                                    </td>
                                    <td class="pl-2 pr-4 sm:pr-6 py-4">
                                        <div class="flex justify-end items-center">
                                            <Link :href="route('payments.edit', payment.id)">
                                                <PencilAltIcon class="h-5 w-5 text-blue-600 cursor-pointer" aria-hidden="true"  />
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-2">
                        <Link :href="route('payments.create', paymentsOpen)">
                            <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600">
                                Agregar Pago
                            </Button>
                        </Link>
                    </div>
                </template>
            </JetDialogModal>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import FormSection from '@/Components/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import Badge from '@/Components/Badge.vue'
import Button from '@/Components/Button.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { CashIcon, UserIcon, BookOpenIcon, PencilAltIcon, TrashIcon, ClipboardCheckIcon, UserAddIcon } from '@heroicons/vue/outline'

const props = defineProps({
    classroom: Object,
})

const loading = ref([])
const lessonsLoading = ref(false)
const open = ref(false)
const subsOpen = ref(false)
const studentsOpen = ref(false)
const paymentsOpen = ref(false)
const students = ref([])
const payments = ref([])
const lessons = ref([])
const paymentsSelected =  ref(null)
const confirming = ref(false)

const price_hr = computed(() => props.classroom.subscriptions[0].price_hr)
const sub_created_at = computed(() => props.classroom.subscriptions[0].created_at)
const studentsList = computed(() => {
    return students.value.filter((student) => !props.classroom.students.map(st => st.user.id).includes(student.id))
})

const form = useForm({
    _method: 'PUT',
    name: props.classroom.name,
    description: props.classroom.description,
})

const submit = () => {
    form.put(route('classrooms.update', props.classroom), {
        preserveScroll: true,
    })
}

const subsForm = useForm({
    price_hr: props.classroom.subscriptions[0].price_hr,
})

const subsSubmit = () => {
    subsForm.post(route('classrooms.subscriptions.store', props.classroom.id), {
        preserveScroll: true,
        onSuccess: () => subsCloseModal(),
    })
}

const confirmModal = () => {
    confirming.value = true
}

const closeConfirm = () => {
    confirming.value = false
}

const confirmingAction = () => {
    if (props.classroom.deleted_at) {
        form.put(route('classrooms.restore', props.classroom.id), {
            preserveScroll: true,
            onSuccess: () => closeConfirm()
        })
    } else {
        form.delete(route('classrooms.destroy', props.classroom.id), {
            preserveScroll: true,
            onSuccess: () => closeConfirm()
        })
    }
}

const openModal = () => {
    open.value = true
}

const closeModal = () => {
    open.value = false
}

const subsOpenModal = () => {
    subsForm.price_hr = price_hr.value
    subsOpen.value = true
}

const subsCloseModal = () => {
    subsOpen.value = false
    subsForm.clearErrors('price_hr')
}

const studentsOpenModal = async () => {
    await getStudents()
    studentsOpen.value = true
}

const studentsCloseModal = () => {
    studentsOpen.value = false
}

const paymentsOpenModal = async (classroomUser, index) => {
    if (classroomUser !== paymentsSelected.value) {
        paymentsSelected.value = classroomUser
        loading.value[index] = true
        await getPayments(classroomUser)
        loading.value[index] = false
    }
    paymentsOpen.value = classroomUser
}

const paymentsCloseModal = () => {
    paymentsOpen.value = false
}

const destroySubs = (id) => {
    useForm({
        _method: 'DELETE'
    }).delete(route('subscriptions.destroy', id), {
        preserveScroll: true,
        onSuccess: () => closeModal()
    })
}

const destroyStudent = (id) => {
    useForm({
        _method: 'DELETE'
    }).delete(route('classroomUsers.destroy', id), {
        preserveScroll: true,
    })
}

const restoreStudent = (id) => {
    useForm({
        _method: 'PUT'
    }).put(route('classroomUsers.restore', id), {
        preserveScroll: true,
    })
}

const getStudents = async () => {
    if (students.value.length === 0) {
        let response = await axios.get('/users/students')
        students.value = response.data
    }
}

const addStudent = (student) => {
    useForm({
        _method: 'POST',
        classroom_id: props.classroom.id,
        user_id: student,
    }).post(route('classroomUsers.store'), {
        preserveScroll: true
    })
}

const getPayments = async (classroomUser) => {
    let response = await axios.get('/classroomUsers/' + classroomUser + '/payments')
    payments.value = response.data
}

const getLessons = async () => {
    if (!lessons.value.length) {
        lessonsLoading.value = true
        let response = await axios.get('/classrooms/' + props.classroom.id + '/lessons')
        lessons.value = response.data
        lessonsLoading.value = false
    }
}
</script>