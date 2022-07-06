<template>
    <AppLayout title="Pagos">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'payments.index'">Pagos</BreadcrumbLink>
                </template>

                <template #static>
                    Modificar Pago
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <FormSection @submitted="submit">
                <template #form>
                    <!-- Payment_date -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="payment_date" value="Fecha del Pago" />
                        <JetInput id="payment_date" v-model="form.payment_date" type="date" class="mt-1 block w-full"/>
                        <JetInputError :message="form.errors.payment_date" class="mt-2" />
                    </div>

                    <!-- Income -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="income" value="Ingreso" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none z-20 pl-4">
                                <span class="text-gray-600">$</span>
                            </div>
                            <JetInput id="income" v-model="form.income" type="text" class="mt-1 block w-full pl-10"/>
                        </div>
                        <JetInputError :message="form.errors.income" class="mt-2" />
                    </div>

                    <!-- Comment -->
                    <div class="col-span-6">
                        <JetLabel for="comment" value="Comentario" />
                        <JetInput id="comment" v-model="form.comment" type="text" class="mt-1 block w-full"/>
                        <JetInputError :message="form.errors.comment" class="mt-2" />
                    </div>

                    <div class="col-span-6">
                        <h2 class="font-semibold text-xl pl-4 sm:pl-6 pr-2 py-2">Alumnos</h2>

                        <div class="relative overflow-x-auto shadow sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 table-fixed">
                                <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                                    <tr>
                                        <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                            Nombre
                                        </th>
                                        <th scope="col" class="px-2 py-3">
                                            Clase
                                        </th>
                                        <th scope="col" class="px-2 py-3">
                                            Suscripción
                                        </th>
                                        <th scope="col" class="px-2 py-3 w-1/6">
                                            Créditos
                                        </th>
                                        <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3 w-1/6">
                                            <span class="sr-only">Desasociar</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="form.students.length === 0" class="bg-white">
                                        <td colspan="4" class="pl-4 sm:pl-6 pr-2 py-4">No hay alumnos asociados al pago.</td>
                                    </tr>
                                    <tr v-else v-for="(studentClass, index) in form.students" :key="studentClass.id" :class="{'border-b': index != form.students.length - 1}" class="bg-white hover:bg-gray-50">
                                        <th class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                            {{ studentClass.user.firstname + ' ' + studentClass.user.lastname }}
                                        </th>
                                        <td class="px-2 py-4">
                                            {{ studentClass.classroom.name }}
                                        </td>
                                        <td class="px-2 py-4">
                                            $ {{ studentClass.price_hr }}
                                        </td>
                                        <td class="px-2">
                                            <JetInput v-model="studentClass.new_credit" type="text" class="py-1 w-full"/>
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

                        <div class="flex flex-row-reverse justify-between mt-2">
                            <Button type="button" class="bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600"
                                :class="{ 'opacity-25': loading }" :disabled="loading"
                                @click="openModal">
                                <span v-if="loading" class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2" role="status" aria-label="loading"></span>
                                Agregar Alumno
                            </Button>
                            <JetInputError v-if="form.errors.students" :message="['Es obligatorio asociar al menos un alumno.']" class="mt-2" />
                        </div>
                    </div>
                </template>

                <template #actions>
                    <Button class="bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !form.isDirty"
                    >
                        <span v-text="form.isDirty ? 'Guardar Cambios' : 'Sin Cambios'"></span>
                    </Button>

                    <Button type="button" class="bg-red-600 hover:bg-red-700 focus:bg-red-800 focus:ring-red-500 active:bg-red-800"
                        :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal"
                    >
                        Eliminar
                    </Button>
                </template>
            </FormSection>

            <!-- Modal de Confirmacion -->
            <JetDialogModal :show="confirming" @close="closeConfirm">
                <template #title>
                    Eliminar Pago
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
                                    <th scope="col" class="px-6 py-3">
                                        Clase
                                    </th>
                                    <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                        <span class="sr-only">Asociar</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="studentsList.length === 0" class="bg-white">
                                    <td colspan="4" class="pl-4 sm:pl-6 pr-2 py-4">No existen alumnos cursando actualmente.</td>
                                </tr>
                                <tr v-else v-for="(student, index) in studentsList" :key="student.id" :class="{'border-b': index != studentsList.length - 1}" class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                        {{ student.user.firstname + ' ' + student.user.lastname }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ student.classroom.name }}
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
import { computed, ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import FormSection from '@/Components/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Button from '@/Components/Button.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import { UserAddIcon, UserRemoveIcon } from '@heroicons/vue/outline'

const props = defineProps({
    payment: Object,
})

const form = useForm({
    _method: 'PUT',
    payment_date: props.payment.payment_date,
    income: props.payment.income,
    comment: props.payment.comment,
    students: props.payment.students,
    original_students: props.payment.students.reduce((selected, student) => {
        selected[student.id] = student.new_credit
        return selected
    }, {})
})

const open = ref(false)
const loading = ref(false)
const students = ref([])
const confirming = ref(false)

const studentsList = computed(() => {
    return students.value.filter((student) => !form.students.map(st => st.id).includes(student.id))
})

watch(() => form.income, () => {
    updateCredit()
})

const updateCredit = () => {
    if (form.students.length) {
        form.students.forEach((student) => {
            student.new_credit = String(Math.trunc((form.income / student.price_hr) / form.students.length))
        })
    }
}

const submit = () => {
    form.transform((data) => ({
        ...data,
        students: data.students.reduce((selected, student) => {
            selected[student.id] = student.new_credit
            return selected
        }, {})
    })).put(route('payments.update', props.payment.id), {
        preserveScroll: true,
    })
}

const getStudents = async () => {
    if (students.value.length === 0) {
        let response = await axios.get('/classroomUsers')
        students.value = response.data
    }
}

const getSubscription = async (student) => {
    let response = await axios.get('/classroomUsers/' + student + '/subscription')
    return response.data.price_hr
}

const openModal = async () => {
    loading.value = true
    await getStudents()
    loading.value = false
    open.value = true
}

const closeModal = () => {
    open.value = false
}

const addStudent = async (student) => {
    student.price_hr = await getSubscription(student.id)
    form.students.push(student)
    updateCredit()
}

const removeStudent = (studentClass) => {
    form.students = form.students.filter((student) => {
        return student !== studentClass
    })
    updateCredit()
}

const confirmModal = () => {
    confirming.value = true
}

const closeConfirm = () => {
    confirming.value = false
}

const confirmingAction = () => {
    form.delete(route('payments.destroy', props.payment.id), {
        preserveScroll: true,
        onSuccess: () => closeConfirm()
    })
}
</script>