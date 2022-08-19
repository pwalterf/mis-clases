<template>
    <AppLayout title="Pagos">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                </template>

                <template #static>
                    Pagos
                </template>
            </Breadcrumb>
            <Link :href="route('payments.create')">
                <SecondaryButton type="button" class="border-none">
                    Nuevo Pago
                </SecondaryButton>
            </Link>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                        <tr>
                            <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                Fecha Pago
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
                            <td colspan="4" class="pl-4 sm:pl-6 pr-2 py-3">No existen clases creadas</td>
                        </tr>
                        <tr v-else v-for="(payment, index) in payments" :key="payment.id" :class="{'border-b': index != payments.length - 1}" class="bg-white hover:bg-gray-50">
                            <td class="pl-4 sm:pl-6 pr-2 py-3">
                                {{ payment.payment_date }}
                            </td>
                            <td class="px-2 py-4">
                                $ {{ payment.income }}
                            </td>
                            <td class="px-2 py-4">
                                {{ payment.comment ?? 'Ninguno' }}
                            </td>
                            <td class="pl-2 pr-4 sm:pr-6 py-3">
                                <div class="flex justify-end items-center gap-2">
                                    <span v-if="loading[index]" class="animate-spin inline-block w-5 h-5 border-[3px] border-current border-t-transparent rounded-full" role="status" aria-label="loading"></span>
                                    <UserGroupIcon v-else class="h-5 w-5 text-gray-600 cursor-pointer" aria-hidden="true" @click="studentsOpen(payment.id, index)" />
                                    <Link :href="route('payments.edit', payment.id)">
                                        <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alumnos en el pago -->
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
                                    Clase
                                </th>
                                <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3 text-center">
                                    Créditos
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="students.length === 0" class="bg-white">
                                <td colspan="3" class="pl-4 sm:pl-6 pr-2 py-4">No existen alumnos asociados al pago.</td>
                            </tr>
                            <tr v-else v-for="(student, index) in students" :key="student.id" :class="{'border-b': index != students.length - 1}" class="bg-white border-b hover:bg-gray-50">
                                <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                    {{ student.user.firstname + ' ' + student.user.lastname }}
                                </th>
                                <td class="px-2 py-4">
                                    {{ student.classroom.name }}
                                </td>
                                <td class="pl-2 pr-4 sm:pr-6 py-4 text-center">
                                    {{ student.new_credit }}
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
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { PencilAltIcon, UserGroupIcon } from '@heroicons/vue/outline'

const props = defineProps({
    payments: Object,
})

const loading = ref([])
const selected = ref(null)
const students = ref([])
const studentsModal = ref(false)

const studentsOpen = async (payment, index) => {
    if (selected === null || selected.value?.id !== payment) {
        loading.value[index] = true
        selected.value = props.payments.find(item => item.id === payment)
        await getStudents(payment)
        loading.value[index] = false
    }
    studentsModal.value = true
}

const studentsClose = () => {
    studentsModal.value = false
}

const getStudents = async (payment) => {
    let response = await axios.get('/payments/' + payment + '/students')
    students.value = response.data
}
</script>