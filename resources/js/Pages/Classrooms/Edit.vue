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
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !form.isDirty">
                            <span v-text="form.isDirty ? 'Guardar Cambios' : 'Sin Cambios'"></span>
                        </PrimaryButton>

                        <SuccessButton v-if="classroom.deleted_at" type="button" :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal">
                            Restaurar
                        </SuccessButton>
                        <DangerButton v-else type="button" :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal">
                            Eliminar
                        </DangerButton>
                    </template>
                </FormSection>
            </div>

            <div class="col-span-6 md:col-span-2">
                <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2">Suscripción</h2>

                <FormSection>
                    <template #form>
                        <!-- Started_at -->
                        <div class="col-span-6">
                            <JetLabel for="started_at" value="Fecha" />
                            <JetInput id="started_at" v-model="lastSubs.started_at" type="date" class="mt-1 block w-full bg-slate-50 text-slate-500" placeholder="hola" disabled />
                        </div>

                        <!-- Price_hr -->
                        <div class="col-span-6">
                            <JetLabel for="price_hr" value="Precio por Hora" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none z-20 pl-4">
                                    <span class="text-gray-600">$</span>
                                </div>
                                <JetInput id="price_hr" v-model="lastSubs.price_hr" type="text" class="mt-1 block w-full pl-10 bg-slate-50 text-slate-500" placeholder="hola" disabled />
                            </div>
                        </div>
                    </template>

                    <template #actions>
                        <PrimaryButton type="button" :class="{ 'opacity-25': subsForm.processing}" :disabled="subsForm.processing" @click="subsOpenModal">
                            Modificar
                        </PrimaryButton>

                        <SecondaryButton type="button" @click="openModal">
                            Historial
                        </SecondaryButton>
                    </template>
                </FormSection>
            </div>

            <div class="col-span-6 mt-2">

                <TabGroup as="div" class="sm:rounded-md shadow outline-none">
                    <TabList class="flex flex-wrap text-sm font-medium text-center text-gray-500 bg-gray-50 sm:rounded-t-md border-b border-gray-200">
                        <Tab v-slot="{ selected }" as="div" class="mr-4 outline-none">
                            <button type="button" class="inline-flex items-center p-4 border-b-2 gap-2" :class="selected ? 'text-purple-600 border-purple-500' : 'border-transparent text-gray-500 hover:text-purple-600'">
                                <UserIcon class="h-5 w-5" aria-hidden="true" />
                                Alumnos
                            </button>
                        </Tab>
                        <Tab v-slot="{ selected }" as="div" class="mr-4 outline-none">
                            <button type="button" class="inline-flex items-center p-4 border-b-2 gap-2" :class="selected ? 'text-purple-600 border-purple-500' : 'border-transparent text-gray-500 hover:text-purple-600'" @click="getLessons">
                                <span v-if="lessonsLoading" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                                <BookOpenIcon v-else class="h-5 w-5" aria-hidden="true" />
                                Lecciones
                            </button>
                        </Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel class="bg-white sm:rounded-b-md">
                            <div class="relative overflow-x-auto">
                                <ClassroomStudentsTable
                                    :items="classroom"
                                    :loading="loading"
                                    @payments-open-modal="paymentsOpenModal"
                                    @student-open-modal="studentOpenModal"
                                    @remove-student="removeStudent"
                                    @restore-student="restoreStudent"
                                />
                            </div>

                            <div class="flex justify-end py-4 px-4 sm:px-6">
                                <SecondaryButton type="button" :class="{ 'opacity-25': form.processing}" :disabled="form.processing || classroom.deleted_at" @click="studentsOpenModal">
                                    <span v-if="studentsLoading" class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2" role="status" aria-label="loading"></span>
                                    Agregar Alumno
                                </SecondaryButton>
                            </div>
                        </TabPanel>
                        <TabPanel class="bg-white sm:rounded-b-md">
                            <div class="relative overflow-x-auto">
                                <LessonsTable
                                    :items="lessons"
                                />
                            </div>

                            <div class="flex justify-end py-4 px-4 sm:px-6">
                                <Link :href="route('lessons.create', classroom.id)">
                                    <SecondaryButton type="button" :class="{ 'opacity-25': form.processing}" :disabled="form.processing || classroom.deleted_at === 'Inactiva'">
                                        Nueva Lección
                                    </SecondaryButton>
                                </Link>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>

            </div>

            <!-- Editar Alumno -->
            <JetDialogModal :show="studentOpen" @close="studentCloseModal">
                <template #title>
                    Editar Alumno
                </template>

                <template #content>
                    <form @submit.prevent="studentSubmit" novalidate>
                        <div class="grid grid-cols-6 gap-4">
                            <!-- Firstname -->
                            <div class="col-span-6 md:col-span-3">
                                <JetLabel for="firstname" value="Nombre" />
                                <JetInput id="firstname" v-model="studentForm.firstname" type="text" class="mt-1 block w-full" :error="studentForm.errors.firstname" required />
                                <JetInputError :message="studentForm.errors.firstname" class="mt-2" />
                            </div>
    
                            <!-- Lastname -->
                            <div class="col-span-6 md:col-span-3">
                                <JetLabel for="lastname" value="Apellido" />
                                <JetInput id="lastname" v-model="studentForm.lastname" type="text" class="mt-1 block w-full" :error="studentForm.errors.lastname" required />
                                <JetInputError :message="studentForm.errors.lastname" class="mt-2" />
                            </div>
    
                            <!-- Email Address -->
                            <div class="col-span-6 md:col-span-5">
                                <JetLabel for="email" value="Email" />
                                <JetInput id="email" v-model="studentForm.email" type="email" class="mt-1 block w-full" :error="studentForm.errors.email" required />
                                <JetInputError :message="studentForm.errors.email" class="mt-2" />
                            </div>
    
                            <!-- Credit -->
                            <div class="col-span-6 md:col-span-1">
                                <JetLabel for="credit" value="Créditos" />
                                <JetInput id="credit" v-model="studentForm.credit" type="text" class="mt-1 block w-full" :error="studentForm.errors.credit" />
                                <JetInputError v-if="studentForm.errors.credit" :message="studentForm.errors.credit" class="mt-2" />
                            </div>
                        </div>
                    </form>
                </template>

                <template #footer>
                    <CancelButton class="mr-4" @click="studentCloseModal">
                        Cancelar
                    </CancelButton>

                    <PrimaryButton @click="studentSubmit" :class="{ 'opacity-25': studentForm.processing }" :disabled="studentForm.processing">
                        Guardar
                    </PrimaryButton>
                </template>
            </JetDialogModal>

            <!-- Modal de Confirmacion -->
            <JetDialogModal :show="confirming" @close="closeConfirm">
                <template #title>
                    <span v-text="classroom.deleted_at ? 'Restaurar' : 'Eliminar'"></span> Clase
                </template>

                <template #content>
                    ¿Esta seguro que desea realizar esta acción?
                </template>

                <template #footer>
                    <CancelButton class="mr-4" @click="closeConfirm">
                        Cancelar
                    </CancelButton>

                    <PrimaryButton :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmingAction">
                        Confirmar
                    </PrimaryButton>
                </template>
            </JetDialogModal>

            <!-- Historial de Suscripciones -->
            <JetDialogModal :show="open" @close="closeModal">
                <template #title>
                    Suscripciones
                </template>

                <template #content>
                    <div class="relative overflow-x-auto shadow sm:rounded-md">
                        <SubscriptionsTable
                            :items="classroom.subscriptions"
                            @destroy-subs="destroySubs"
                        />
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
                        <!-- Started_at -->
                        <div class="col-span-6">
                            <JetLabel for="started_at" value="Fecha Suscripción" />
                            <JetInput id="started_at" v-model="subsForm.started_at" type="date" class="mt-1 block w-full" :error="subsForm.errors.started_at" required />
                            <JetInputError :message="subsForm.errors.started_at" class="mt-2" />
                        </div>

                        <!-- Price_hr -->
                        <div class="col-span-6 mt-4">
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
                    <CancelButton class="mr-4" @click="subsCloseModal">
                        Cancelar
                    </CancelButton>

                    <PrimaryButton @click="subsSubmit" :class="{ 'opacity-25': subsForm.processing }" :disabled="subsForm.processing">
                        Confirmar
                    </PrimaryButton>
                </template>
            </JetDialogModal>

            <!-- Listado de Alumnos -->
            <JetDialogModal :show="studentsOpen" @close="studentsCloseModal">
                <template #title>
                    Alumnos
                </template>

                <template #content>
                    <div class="relative overflow-x-auto shadow sm:rounded-md">
                        <StudentsTable
                            :items="studentsList"
                            @add-student="addStudent"
                        />
                    </div>
                </template>
            </JetDialogModal>

            <!-- Historial de Pagos -->
            <JetDialogModal :show="paymentsOpen != false" @close="paymentsCloseModal">
                <template #title>
                    Pagos
                </template>

                <template #content>
                    <div class="relative overflow-x-auto shadow sm:rounded-md">
                        <PaymentsTable
                            :items="payments"
                            :actions="['edit']"
                            :rows="5"
                        />
                    </div>

                    <div class="flex justify-end mt-2">
                        <Link :href="route('payments.create', paymentsSelected)">
                            <SecondaryButton type="button">
                                Agregar Pago
                            </SecondaryButton>
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
import CancelButton from '@/Components/Buttons/CancelButton.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import SuccessButton from '@/Components/Buttons/SuccessButton.vue'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import ClassroomStudentsTable from '@/Components/Tables/ClassroomStudentsTable.vue'
import LessonsTable from '@/Components/Tables/LessonsTable.vue'
import StudentsTable from '@/Components/Tables/StudentsTable.vue'
import SubscriptionsTable from '@/Components/Tables/SubscriptionsTable.vue'
import PaymentsTable from '@/Components/Tables/PaymentsTable.vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { UserIcon, BookOpenIcon, PencilAltIcon, TrashIcon } from '@heroicons/vue/outline'

const props = defineProps({
    classroom: Object,
})

const loading = ref([])
const studentsLoading = ref(false)
const lessonsLoading = ref(false)
const open = ref(false)
const subsOpen = ref(false)
const studentsOpen = ref(false)
const studentOpen = ref(false)
const paymentsOpen = ref(false)
const students = ref([])
const payments = ref([])
const lessons = ref([])
const paymentsSelected = ref(null)
const confirming = ref(false)

const lastSubs = computed(() => {
    return props.classroom.subscriptions[0] ?? {price_hr: '-'}
})

const studentsList = computed(() => {
    return students.value.filter((student) => !props.classroom.students.map(st => st.user.id).includes(student.id))
})

const form = useForm({
    _method: 'PUT',
    name: props.classroom.name,
    description: props.classroom.description,
})

const submit = () => {
    form.put(route('classrooms.update', props.classroom.id), {
        preserveScroll: true,
    })
}

const subsForm = useForm({
    price_hr: '',
    started_at: '',
})

const subsSubmit = () => {
    subsForm.post(route('classrooms.subscriptions.store', props.classroom.id), {
        preserveScroll: true,
        onSuccess: () => subsCloseModal(),
    })
}

const studentForm = useForm({
    id: '',
    firstname: '',
    lastname: '',
    email: '',
    credit: '',
})

const studentSubmit = () => {
    studentForm.put(route('students.update', studentForm.id), {
        preserveScroll: true,
        onSuccess: () => studentCloseModal(),
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
    subsOpen.value = true
}

const subsCloseModal = () => {
    subsOpen.value = false
    subsForm.reset()
    subsForm.clearErrors()
}

const studentOpenModal = (student_id) => {
    let response = JSON.parse(JSON.stringify(props.classroom.students.find((student) => student.id === student_id)))
    studentForm.id = response.id
    studentForm.firstname = response.user.firstname
    studentForm.lastname = response.user.lastname
    studentForm.email = response.user.email
    studentForm.credit = response.credit
    studentOpen.value = true
}

const studentCloseModal = () => {
    studentOpen.value = false
    studentForm.reset()
    studentForm.clearErrors()
}

const studentsOpenModal = async () => {
    await getStudents()
    studentsOpen.value = true
}

const studentsCloseModal = () => {
    studentsOpen.value = false
}

const paymentsOpenModal = async (student_id) => {
    if (student_id !== paymentsSelected.value) {
        paymentsSelected.value = student_id
        loading.value[student_id] = true
        await getPayments(student_id)
        loading.value[student_id] = false
    }
    paymentsOpen.value = true
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

const addStudent = (student_id) => {
    useForm({
        _method: 'POST',
        classroom_id: props.classroom.id,
        user_id: student_id,
    }).post(route('classrooms.students.add'), {
        preserveScroll: true
    })
}

const removeStudent = (student_id) => {
    useForm({
        _method: 'POST',
        classroom_id: props.classroom.id,
        user_id: student_id,
    }).post(route('classrooms.students.remove'), {
        preserveScroll: true,
    })
}

const restoreStudent = (student_id) => {
    useForm({
        _method: 'POST',
        classroom_id: props.classroom.id,
        user_id: student_id,
    }).post(route('classrooms.students.restore'), {
        preserveScroll: true,
    })
}

const getStudents = async () => {
    if (students.value.length === 0) {
        studentsLoading.value = true
        let response = await axios.get('/users/students')
        students.value = response.data
        studentsLoading.value = false
    }
}

const getPayments = async (student_id) => {
    let response = await axios.get(route('students.payments', student_id))
    payments.value = response.data
}

const getLessons = async () => {
    if (!lessons.value.length) {
        lessonsLoading.value = true
        let response = await axios.get(route('classrooms.lessons', props.classroom.id))
        lessons.value = response.data
        lessonsLoading.value = false
    }
}
</script>