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
            <div class="relative overflow-x-auto shadow sm:rounded-md">
                <PaymentsTable
                    :items="payments"
                    :loading="loading"
                    @students-open="studentsOpen"
                />
            </div>
        </div>

        <!-- Alumnos en el pago -->
        <JetDialogModal :show="studentsModal" @close="studentsClose">
            <template #title>
                Alumnos
            </template>

            <template #content>
                <div class="relative overflow-x-auto shadow sm:rounded-md">
                    <PaymentStudentsTable
                        :items="students"
                        :headers="['user.firstname', 'classroom.name', 'new_credit', 'actions']"
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
import PaymentsTable from '@/Components/Tables/PaymentsTable.vue'
import PaymentStudentsTable from '../../Components/Tables/PaymentStudentsTable.vue'

const props = defineProps({
    payments: Object,
})

const loading = ref([])
const selected = ref(null)
const students = ref([])
const studentsModal = ref(false)

const studentsOpen = async (payment_id) => {
    if (selected === null || selected.value?.id !== payment_id) {
        loading.value[payment_id] = true
        selected.value = props.payments.find(item => item.id === payment_id)
        await getStudents(payment_id)
        loading.value[payment_id] = false
    }
    studentsModal.value = true
}

const studentsClose = () => {
    studentsModal.value = false
}

const getStudents = async (payment_id) => {
    let response = await axios.get(route('payments.students', payment_id))
    students.value = response.data
}
</script>