<template>
    <AppLayout title="Clases">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'classrooms.index'">Salas de Clases</BreadcrumbLink>
                </template>

                <template #static>
                    Información
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-6 gap-4">
            <div class="col-span-6 md:col-span-4">
                <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2">Clase</h2>

                <FormSection>
                    <template #form>
                        <!-- Name -->
                        <div class="col-span-6">
                            <JetLabel for="name" value="Nombre" />
                            <JetInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" disabled/>
                        </div>

                        <!-- Description -->
                        <div class="col-span-6">
                            <JetLabel for="description" value="Descripción" />
                            <JetInput id="description" v-model="form.description" type="text" class="mt-1 block w-full" disabled/>
                        </div>
                    </template>

                    <template #actions>
                        <Button class="bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700" disabled>
                            Inactiva
                        </Button>

                        <Button type="button" class="bg-green-700 hover:bg-green-800 focus:bg-green-900 focus:ring-green-600 active:bg-green-900"
                            :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal"
                        >
                            Restaurar
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
                            <JetInput id="sub_created_at" v-model="sub_created_at" type="text" class="mt-1 block w-full" disabled/>
                        </div>

                        <!-- Price_hr -->
                        <div class="col-span-6">
                            <JetLabel for="price_hr" value="Precio por Hora" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none z-20 pl-4">
                                    <span class="text-gray-600">$</span>
                                </div>
                                <JetInput id="price_hr" v-model="price_hr" type="text" class="mt-1 block w-full pl-10" disabled/>
                            </div>
                        </div>
                    </template>

                    <template #actions>
                        <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600" @click="openModal">
                            Historial
                        </Button>
                    </template>
                </FormSection>
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
                                <th scope="col" class="px-2 py-3 text-center">
                                    Estado
                                </th>
                                <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                    <span class="sr-only">Editar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="classroom.classroomUsers.length === 0" class="bg-white">
                                <td colspan="5" class="pl-4 sm:pl-6 pr-2 py-4">No hay alumnos asociados a la clase.</td>
                            </tr>
                            <tr v-else v-for="(student, index) in classroom.classroomUsers" :key="student.id" :class="{'border-b': index != classroom.classroomUsers.length - 1}" class="bg-white hover:bg-gray-50">
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
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-2">
                    <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600" disabled>
                        Agregar Alumno
                    </Button>
                </div>
            </div>

            <!-- Modal de Confirmacion -->
            <JetDialogModal :show="confirming" @close="closeConfirm">
                <template #title>
                    Restaurar Clase
                </template>

                <template #content>
                    ¿Esta seguro que desea realizar esta acción?
                </template>

                <template #footer>
                    <Button class="bg-gray-500 hover:bg-gray-600 focus:bg-gray-700 focus:ring-gray-400 active:bg-gray-700" @click="closeConfirm">
                        Cancelar
                    </Button>

                    <Button class="ml-4 bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="confirmingAction"
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
                                    <th scope="col" class="px-6 py-3">
                                        Creada
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="classroom.subscriptions.length === 0" class="bg-white">
                                    <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No existen clases creadas</td>
                                </tr>
                                <tr v-else v-for="(subscription, index) in classroom.subscriptions" :key="subscription.id" :class="{'border-b': index != classroom.subscriptions.length - 1}" class="bg-white border-b hover:bg-gray-50">
                                    <td scope="row" class="pl-4 sm:pl-6 pr-2 py-4">
                                        $ {{ subscription.price_hr }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ subscription.created_at }}
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
                                    <th scope="col" class="px-6 py-3">
                                        Importe
                                    </th>
                                    <th scope="col" class="px-6 py-3">
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
                                <tr v-else v-for="(payment, index) in payments" :key="payment.id" :class="{'border-b': index != payments.length - 1}" class="bg-white border-b hover:bg-gray-50">
                                    <td scope="row" class="pl-4 sm:pl-6 pr-2 py-4">
                                        {{ payment.payment_date }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ payment.income }}
                                    </td>
                                     <td class="px-6 py-4">
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
import Badge from '@/Components/Badge.vue'
import Button from '@/Components/Button.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { CashIcon, PencilAltIcon } from '@heroicons/vue/outline'

const props = defineProps({
    classroom: Object,
})

const loading = ref([])
const open = ref(false)
const paymentsOpen = ref(false)
const payments = ref([])
const confirming = ref(false)

const price_hr = computed(() => props.classroom.subscriptions[0].price_hr)
const sub_created_at = computed(() => props.classroom.subscriptions[0].created_at)

const form = useForm({
    _method: 'PUT',
    name: props.classroom.name,
    description: props.classroom.description,
})

const confirmModal = () => {
    confirming.value = true
}

const closeConfirm = () => {
    confirming.value = false
}

const confirmingAction = () => {
    useForm({
        _method: 'PUT'
    }).put(route('classrooms.restore', props.classroom.id), {
        preserveScroll: true,
    })
}

const openModal = () => {
    open.value = true
}

const closeModal = () => {
    open.value = false
}

const paymentsOpenModal = async (classroomUser, index) => {
    loading.value[index] = true
    await getPayments(classroomUser)
    loading.value[index] = false
    paymentsOpen.value = classroomUser
}

const paymentsCloseModal = () => {
    paymentsOpen.value = false
}

const getPayments = async (classroomUser) => {
    let response = await axios.get('/classroomUsers/' + classroomUser + '/payments')
    payments.value = response.data
}
</script>