<template>
    <AppLayout title="Usuarios">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                </template>

                <template #static>
                    Usuarios
                </template>
            </Breadcrumb>
            <Link :href="route('users.create')">
                <SecondaryButton type="button" class="border-none">
                    Nuevo Usuario
                </SecondaryButton>
            </Link>
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
                                Email
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Creado
                            </th>
                            <th scope="col" class="px-2 py-3">
                                Rol
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
                        <tr v-for="(user, index) in users" :key="user.id" :class="{'border-b': index != users.length - 1}" class="bg-white hover:bg-gray-50">
                            <th scope="row" class="pl-4 sm:pl-6 pr-2 py-3 text-gray-900">
                                {{ user.firstname + ' ' + user.lastname }}
                            </th>
                            <td class="px-2 py-4">
                                {{ user.email }}
                            </td>
                            <td class="px-2 py-4">
                                {{ user.created_at }}
                            </td>
                            <td class="px-2 py-4 capitalize">
                                {{ user.roles }}
                            </td>
                            <td class="px-2 py-4 text-center">
                                <Badge :class="user.deleted_at ? 'bg-red-600' : 'bg-green-600'">
                                    {{ user.deleted_at ? 'Inactivo' : 'Activo' }}
                                </Badge>
                            </td>
                            <td class="pl-2 pr-4 sm:pr-6 py-3">
                                <div class="flex justify-end items-center">
                                    <Link :href="route('users.edit', user.id)" class="p-1">
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
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Badge from '@/Components/Badge.vue'
import { PencilAltIcon } from '@heroicons/vue/outline'

const props = defineProps({
    users: Object
})

</script>