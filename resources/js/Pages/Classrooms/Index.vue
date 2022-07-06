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
                    <Button type="button" class="border-none bg-purple-400 hover:bg-purple-500 focus:bg-purple-600 focus:ring-purple-300 active:bg-purple-600">
                        Nueva Clase
                    </Button>
                    <!-- <JetButton class="border-none font-medium px-3 bg-blue-800 hover:bg-blue-700 active:bg-blue-900 focus:border-blue-900 focus:ring-blue-300">Nueva Clase</JetButton> -->
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-purple-700 uppercase bg-purple-100">
                        <tr>
                            <th scope="col" class="pl-4 sm:pl-6 pr-2 py-3">
                                Nombre
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Creada
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Precio/hr
                            </th>
                            <th scope="col" class="px-2 py-3 hidden md:table-cell">
                                Estudiantes
                            </th>
                            <th scope="col" class="px-2 py-3 hidden md:table-cell">
                                Lecciones
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
                        <tr v-if="classrooms.length === 0" class="bg-white">
                            <td colspan="7" class="pl-4 sm:pl-6 pr-2 py-4">No existen clases creadas</td>
                        </tr>
                        <tr v-else v-for="(classroom, index) in classrooms" :key="classroom.id" :class="{'border-b': index != classrooms.length - 1}" class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="pl-4 sm:pl-6 pr-2 py-4 text-gray-900">
                                {{ classroom.name }}
                            </th>
                            <td class="px-2 py-4">
                                {{ classroom.created_at }}
                            </td>
                            <td class="px-2 py-4">
                                {{ classroom.price_hr }}
                            </td>
                            <td class="px-2 py-4 hidden md:table-cell">
                                Estudiantes
                            </td>
                            <td class="px-2 py-4 hidden md:table-cell">
                                Lecciones
                            </td>
                            <td class="px-2 py-4 text-center">
                                <Badge :class="classroom.deleted_at ? 'bg-red-500' : 'bg-green-500'">
                                    {{ classroom.deleted_at ? 'Inactiva' : 'Activa' }}
                                </Badge>
                            </td>
                            <td class="pl-2 pr-4 sm:pr-6 py-3">
                                <div class="flex justify-end items-center gap-2">
                                    <Link :href="route('classrooms.edit', classroom.id)" class="p-1">
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
import Badge from '@/Components/Badge.vue'
import Button from '@/Components/Button.vue'
import { PencilAltIcon } from '@heroicons/vue/outline'

const props = defineProps({
    classrooms: Object,
})
</script>