<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Alert from '@/Components/Alert.vue';
import { Dialog, DialogOverlay, Menu, MenuButton, MenuItem, MenuItems, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CogIcon, LogoutIcon, MenuIcon, ChevronDownIcon, HomeIcon, UserIcon, AcademicCapIcon, BookOpenIcon, CashIcon } from '@heroicons/vue/outline'

defineProps({
    title: String,
});

const sidebarOpened = ref(false)

const avatar = computed(
    () => usePage().props.value.user.firstname.charAt(0) + usePage().props.value.user.lastname.charAt(0)
)

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<template>
<div>
    <Head :title="title" />
    <JetBanner />

    <div class="min-h-screen bg-gray-100">

        <!-- Mobile Sidebar & Overlay -->
        <TransitionRoot :show="sidebarOpened">
            <Dialog as="div" @close="sidebarOpened = false" class="md:hidden fixed inset-0 z-40">

                <!-- Sidebar -->
                <TransitionChild
                    enter="transition ease-in-out duration-200 transform"
                    enter-from="-translate-x-full"
                    enter-to="translate-x-0"
                    leave="transition ease-in-out duration-200 transform"
                    leave-from="translate-x-0"
                    leave-to="-translate-x-full"
                    as="template">
                    <div class="md:hidden relative flex flex-col w-72 h-full bg-gray-50 border-r border-gray-200 z-10">

                        <!-- Close Sidebar Button -->
                        <!-- <button
                            @click="sidebarOpened = false"
                            class="hover:ring-2 hover:ring-gray-300 flex absolute top-2 right-2 justify-center items-center w-10 h-10 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-600"
                            type="button" value="Close sidebar">
                            <XIcon class="w-5 h-5"/>
                        </button> -->

                        <!-- Application Logo -->
                        <!-- <div class="px-6 pt-8 pb-4 flex justify-center items-center">
                            <a href="/">
                                <JetApplicationLogo class="h-7 w-48"/>
                            </a>
                        </div> -->

                        <!-- Sidebar Items -->
                        <div class="overflow-y-auto flex-1 pt-6">
                            <h3 class="mx-6 mb-2 text-xs tracking-widest text-gray-400 uppercase">
                                Menu Principal
                            </h3>

                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                <HomeIcon class="h-5 w-5 mr-3" aria-hidden="true" />
                                Dashboard
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="is('admin')" :href="route('users.index')" :active="route().current('users.*')">
                                <UserIcon class="h-5 w-5 mr-3" aria-hidden="true" />
                                Usuarios
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="is('admin')" :href="route('classrooms.index')" :active="route().current('classrooms.*')">
                                <AcademicCapIcon class="h-5 w-5 mr-3" aria-hidden="true" />
                                Clases
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="is('admin')" :href="route('lessons.index')" :active="route().current('lessons.*')">
                                <BookOpenIcon class="h-5 w-5 mr-3" aria-hidden="true" />
                                Lecciones
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="is('admin')" :href="route('payments.index')" :active="route().current('payments.*')">
                                <CashIcon class="h-5 w-5 mr-3" aria-hidden="true" />
                                Pagos
                            </ResponsiveNavLink>
                        </div>

                    </div>
                </TransitionChild>

                <!-- Overlay -->
                <TransitionChild
                    enter="transition-opacity ease-linear duration-200"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-200"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                    as="template">
                    <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-50"></DialogOverlay>
                </TransitionChild>
            </Dialog>
        </TransitionRoot>

        <!-- Container -->
        <div class="flex-1">

            <!-- Nav -->
            <nav class="sticky top-0 inset-0 bg-gray-50 border-b z-40">
                <div class="flex justify-between h-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex">
                        <!-- Open Sidebar Button -->
                        <div class="flex items-center">
                            <button
                                @click="sidebarOpened = true"
                                class="mr-4 md:hidden flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none"
                                type="button" value="Open sidebar">
                                <MenuIcon class="h-6 w-6"/>
                            </button>
                        </div>
    
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <JetApplicationLogo class="block h-9" />
                            </Link>
                        </div>
    
                        <!-- Navigation Links -->
                        <div class="hidden md:flex md:-my-px ml-10 space-x-6 lg:space-x-8">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>
    
                            <NavLink v-if="is('admin')" :href="route('users.index')" :active="route().current('users.*')">
                                Usuarios
                            </NavLink>
    
                            <NavLink v-if="is('admin')" :href="route('classrooms.index')" :active="route().current('classrooms.*')">
                                Clases
                            </NavLink>
    
                            <NavLink v-if="is('admin')" :href="route('lessons.index')" :active="route().current('lessons.*')">
                                Lecciones
                            </NavLink>
    
                            <NavLink v-if="is('admin')" :href="route('payments.index')" :active="route().current('payments.*')">
                                Pagos
                            </NavLink>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <Menu as="div" class="relative flex-shrink-0">
                            <MenuButton class="md:hidden flex p-2 border-2 border-transparent rounded-full bg-gray-800 text-white focus:outline-none focus:border-gray-300 transition">
                                {{ avatar }}
                            </MenuButton>
                            <MenuButton class="hidden md:inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none transition">
                                {{ $page.props.user.firstname }}
                                <ChevronDownIcon class="ml-2 -mr-1 h-5 w-5 text-gray-500 hover:text-gray-700" aria-hidden="true" />
                            </MenuButton>
                
                            <transition
                                enter-active-class="transition duration-100 ease-out transform"
                                enter-from-class="opacity-0 scale-90"
                                enter-to-class="opacity-100 scale-100"
                                leave-active-class="transition duration-100 ease-in transform"
                                leave-from-class="opacity-100 scale-100"
                                leave-to-class="opacity-0 scale-90"
                                >
                                <MenuItems class="overflow-hidden absolute right-0 mt-2 w-48 bg-white rounded-md border shadow-lg origin-top-right focus:outline-none">
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Administrar Cuenta
                                    </div>
                                    <MenuItem v-slot="{active}">
                                        <a :href="route('profile.show')" :class="{'bg-gray-100': active}" class="flex py-2 px-4 text-sm text-gray-700">
                                            <CogIcon class="h-4 w-4 mr-3" aria-hidden="true" />
                                            Mi Perfil
                                        </a>
                                    </MenuItem>
                                    <MenuItem v-slot="{active}">
                                        <form @submit.prevent="logout">
                                            <button type="submit" :class="{'bg-gray-100': active}" class="flex w-full text-left py-2 px-4 text-sm text-gray-700">
                                                <LogoutIcon class="h-4 w-4 mr-3" aria-hidden="true" />
                                                Cerrar Sesión
                                            </button>
                                        </form>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="flex justify-between items-center max-w-7xl h-16 mx-auto py-3 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="pb-4">
                <div class="py-4">
                    <Alert />
                </div>
                
                <slot />
            </main>
        </div>
    </div>
</div>






</template>
