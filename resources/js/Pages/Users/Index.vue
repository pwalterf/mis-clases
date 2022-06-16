<template>
    <AppLayout title="Usuarios">
        <template #header>
            <div class="flex flex-row justify-between">
                <Breadcrumb>
                    <template #links>
                        <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    </template>

                    <template #static>
                        Usuarios
                    </template>
                </Breadcrumb>
                <Link :href="route('users.create')">
                    <JetButton class="border-none font-medium px-3 bg-blue-800 hover:bg-blue-700 active:bg-blue-900 focus:border-blue-900 focus:ring-blue-300">Nuevo Usuario</JetButton>
                </Link>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                #
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Creado
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Rol
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                {{ user.id }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ user.firstname + ' ' + user.lastname }}
                            </th>
                            <td class="px-6 py-4">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ user.created_at }}
                            </td>
                            <td class="px-6 py-4 uppercase">
                                {{ user.roleNames }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <Badge :class="user.deleted_at ? 'bg-red-500' : 'bg-green-500'">
                                    {{ user.deleted_at ? 'Inactivo' : 'Activo' }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link :href="route('users.edit', user.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</Link>
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
import JetButton from '@/Jetstream/Button.vue'
import Badge from '@/Components/Badge.vue'

const props = defineProps({
    users: Object
})

</script>