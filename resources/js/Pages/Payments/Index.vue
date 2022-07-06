<template>
    <AppLayout title="Pagos">
        <template #header>
            <div class="flex flex-row justify-between">
                <Breadcrumb>
                    <template #links>
                        <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    </template>

                    <template #static>
                        Listado de Pagos
                    </template>
                </Breadcrumb>
                <Link :href="route('payments.create')">
                    <Button type="button" class="border-none bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600">
                        Nuevo Pago
                    </Button>
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                            <th scope="col" class="px-2 py-3 hidden md:table-cell">
                                Estudiantes
                            </th>
                            <th scope="col" class="pl-2 pr-4 sm:pr-6 py-3">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="payments.length === 0" class="bg-white">
                            <td colspan="5" class="pl-4 sm:pl-6 pr-2 py-3">No existen clases creadas</td>
                        </tr>
                        <tr v-else v-for="(payment, index) in payments" :key="payment.id" :class="{'border-b': index != payments.length - 1}" class="bg-white border-b hover:bg-gray-50">
                            <td class="pl-4 sm:pl-6 pr-2 py-3">
                                {{ payment.payment_date }}
                            </td>
                            <td class="px-2 py-4">
                                {{ payment.income }}
                            </td>
                            <td class="px-2 py-4">
                                {{ payment.comment ?? 'Ninguno' }}
                            </td>
                            <td class="px-2 py-4 hidden md:table-cell">
                                Estudiantes
                            </td>
                            <td class="pl-2 pr-4 sm:pr-6 py-3">
                                <div class="flex justify-end items-center">
                                    <Link :href="route('payments.edit', payment.id)" class="p-1">
                                        <PencilAltIcon class="h-5 w-5 text-blue-600" aria-hidden="true" />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import Button from '@/Components/Button.vue'
import { PencilAltIcon } from '@heroicons/vue/outline'

const props = defineProps({
    payments: Object,
})
</script>