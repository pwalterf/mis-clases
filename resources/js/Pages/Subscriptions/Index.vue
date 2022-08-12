<template>
    <AppLayout title="Clases">
        <template #header>
            <div class="flex flex-row justify-between">
                <Breadcrumb>
                    <template #links>
                        <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    </template>

                    <template #static>
                        Salas de Clases
                    </template>
                </Breadcrumb>
                <Link :href="route('classrooms.create')">
                    <SecondaryButton type="button" class="border-none">
                        Nueva Clase
                    </SecondaryButton>
                    <!-- <JetButton class="border-none font-medium px-3 bg-blue-800 hover:bg-blue-700 active:bg-blue-900 focus:border-blue-900 focus:ring-blue-300">Nueva Clase</JetButton> -->
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Precio / hr
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Creada
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="subscriptions.length === 0" class="bg-white border-b">
                            <td colspan="8" class="px-6 py-4">No existen clases creadas</td>
                        </tr>
                        <tr v-else v-for="subscription in subscriptions" :key="subscription.id" class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                {{ subscription.id }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ subscription.price_hr }}
                            </th>
                            <td class="px-6 py-4">
                                {{ subscription.created_at }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link :href="route('subscriptions.edit', subscription.id)" class="font-medium text-blue-600 hover:underline">Editar</Link>
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
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'

const props = defineProps({
    subscriptions: Object,
})
</script>