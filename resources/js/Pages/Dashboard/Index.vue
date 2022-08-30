<template>
    <AppLayout title="Clases">
        <template #header>
            <Breadcrumb>
                <template #static>
                    Inicio
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-8 gap-4 lg:gap-6 justify-items-stretch">
                <div class="col-span-8 sm:col-span-4 lg:col-span-2">
                    <div class="block p-4 max-w-full bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100">
                        <div class="flex justify-between">
                            <div>
                                <h5 class="font-semibold font-mono text-gray-400 uppercase">Clases Activas</h5>
                                <p class="text-xl font-bold text-gray-900">{{ classroomsPanel.count }}</p>
                            </div>
                            <div class="flex rounded-full w-12 h-12 bg-orange-500 items-center justify-center">
                                <AcademicCapIcon class="h-5 w-5 text-white" aria-hidden="true" />
                            </div>
                        </div>

                        <div class="flex mt-4 text-sm">
                            <span class="flex mr-2 font-semibold gap-1" :class="{'text-green-600': classroomsPanel.lastMonth > 0, 'text-red-600': classroomsPanel.lastMonth < 0, 'text-gray-500': classroomsPanel.lastMonth === 0}">
                                <ArrowUpIcon v-if="classroomsPanel.lastMonth > 0" class="h-4 w-4" aria-hidden="true" />
                                <ArrowDownIcon v-else-if="classroomsPanel.lastMonth < 0" class="h-4 w-4" aria-hidden="true" />
                                <MenuAlt4Icon v-else class="h-4 w-4" aria-hidden="true" />
                                {{ classroomsPanel.lastMonth }}%
                            </span>
                            <span class="text-gray-400">
                                el último mes
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-span-8 sm:col-span-4 lg:col-span-2">
                    <div class="block p-4 max-w-full bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100">
                        <div class="flex justify-between">
                            <div>
                                <h5 class="font-semibold font-mono text-gray-400 uppercase">Alumnos Activos</h5>
                                <p class="text-xl font-bold text-gray-900">{{ studentsPanel.count }}</p>
                            </div>
                            <div class="flex rounded-full w-12 h-12 bg-yellow-500 items-center justify-center">
                                <UsersIcon class="h-5 w-5 text-white" aria-hidden="true" />
                            </div>
                        </div>

                        <div class="flex mt-4 text-sm">
                            <span class="flex mr-2 font-semibold gap-1" :class="{'text-green-600': studentsPanel.lastMonth > 0, 'text-red-600': studentsPanel.lastMonth < 0, 'text-gray-500': studentsPanel.lastMonth === 0}">
                                <ArrowUpIcon v-if="studentsPanel.lastMonth > 0" class="h-4 w-4" aria-hidden="true" />
                                <ArrowDownIcon v-else-if="studentsPanel.lastMonth < 0" class="h-4 w-4" aria-hidden="true" />
                                <MenuAlt4Icon v-else class="h-4 w-4" aria-hidden="true" />
                                {{ studentsPanel.lastMonth }}%
                            </span>
                            <span class="text-gray-400">
                                el último mes
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-span-8 sm:col-span-4 lg:col-span-2">
                    <div class="block p-4 max-w-full bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100">
                        <div class="flex justify-between">
                            <div>
                                <h5 class="font-semibold font-mono text-gray-400 uppercase">Precio/hr Prom.</h5>
                                <p class="text-xl font-bold text-gray-900">$ {{ subsPanel.average }}</p>
                            </div>
                            <div class="flex rounded-full w-12 h-12 bg-blue-500 items-center justify-center">
                                <ReceiptTaxIcon class="h-5 w-5 text-white" aria-hidden="true" />
                            </div>
                        </div>

                        <div class="flex mt-4 text-sm">
                            <span class="flex mr-2 font-semibold gap-1" :class="{'text-green-600': subsPanel.lastMonthNumber > 0, 'text-red-600': subsPanel.lastMonthNumber < 0, 'text-gray-500': subsPanel.lastMonthNumber === 0}">
                                <ArrowUpIcon v-if="subsPanel.lastMonthNumber > 0" class="h-4 w-4" aria-hidden="true" />
                                <ArrowDownIcon v-else-if="subsPanel.lastMonthNumber < 0" class="h-4 w-4" aria-hidden="true" />
                                <MenuAlt4Icon v-else class="h-4 w-4" aria-hidden="true" />
                                {{ subsPanel.lastMonth }}%
                            </span>
                            <span class="text-gray-400">
                                el último mes
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-span-8 sm:col-span-4 lg:col-span-2">
                    <div class="block p-4 max-w-full bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100">
                        <div class="flex justify-between">
                            <div>
                                <h5 class="font-semibold font-mono text-gray-400 uppercase">Ingresos / Mes</h5>
                                <p class="text-xl font-bold text-gray-900">$ {{ incomePanel.income }}</p>
                            </div>
                            <div class="flex rounded-full w-12 h-12 bg-gray-500 items-center justify-center">
                                <CashIcon class="h-5 w-5 text-white" aria-hidden="true" />
                            </div>
                        </div>

                        <div class="flex mt-4 text-sm">
                            <span class="flex mr-2 font-semibold gap-1" :class="{'text-green-600': incomePanel.lastMonthNumber > 0, 'text-red-600': incomePanel.lastMonthNumber < 0, 'text-gray-500': incomePanel.lastMonthNumber === 0}">
                                <ArrowUpIcon v-if="incomePanel.lastMonthNumber > 0" class="h-4 w-4" aria-hidden="true" />
                                <ArrowDownIcon v-else-if="incomePanel.lastMonthNumber < 0" class="h-4 w-4" aria-hidden="true" />
                                <MenuAlt4Icon v-else class="h-4 w-4" aria-hidden="true" />
                                {{ incomePanel.lastMonth }}%
                            </span>
                            <span class="text-gray-400">
                                el último mes
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-span-8 lg:col-span-4">
                    <div class="block p-4 min-w-full h-96 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100">
                        <h5 class="font-semibold font-mono text-gray-400 uppercase">Alumnos Deudores</h5>
                    </div>
                </div>

                <div class="col-span-8 lg:col-span-4">
                    <div class="block p-4 min-w-full h-96 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100">
                        <h5 class="font-semibold font-mono text-gray-400 uppercase">Ingresos Mensuales</h5>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import { ArrowUpIcon, ArrowDownIcon, MenuAlt4Icon } from '@heroicons/vue/outline'
import { AcademicCapIcon, UsersIcon, CashIcon, ReceiptTaxIcon } from '@heroicons/vue/solid'

const props = defineProps({
    classroomsPanel: Object,
    studentsPanel: Object,
    subsPanel: Object,
    incomePanel: Object,
})
</script>