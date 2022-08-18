<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import Alert from '@/Components/Alert.vue';
import { CogIcon, LogoutIcon } from '@heroicons/vue/outline'

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <JetBanner />

        <div class="min-h-screen bg-gray-100">

            <nav class="bg-white sticky top-0 inset-0 shadow z-40">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')" class="flex items-center">
                                    <JetApplicationMark class="block h-9 w-auto" />
                                    <span class="font-semibold ml-4">Mis Clases</span>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <JetNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </JetNavLink>

                                <JetNavLink v-if="is('admin')" :href="route('users.index')" :active="route().current('users.*')">
                                    Usuarios
                                </JetNavLink>

                                <JetNavLink v-if="is('admin')" :href="route('classrooms.index')" :active="route().current('classrooms.*')">
                                    Clases
                                </JetNavLink>

                                <JetNavLink v-if="is('admin')" :href="route('lessons.index')" :active="route().current('lessons.*')">
                                    Lecciones
                                </JetNavLink>

                                <JetNavLink v-if="is('admin')" :href="route('payments.index')" :active="route().current('payments.*')">
                                    Pagos
                                </JetNavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <JetDropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.firstname }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Administrar Cuenta
                                        </div>

                                        <JetDropdownLink :href="route('profile.show')">
                                            <div class="flex items-center gap-2">
                                                <CogIcon class="h-4 w-4" aria-hidden="true" />
                                                Mi Perfil
                                            </div>
                                        </JetDropdownLink>

                                        <div class="border-t border-gray-100" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <JetDropdownLink as="button">
                                                <div class="flex items-center gap-2">
                                                    <LogoutIcon class="h-4 w-4" aria-hidden="true" />
                                                    Cerrar Sesión
                                                </div>
                                            </JetDropdownLink>
                                        </form>
                                    </template>
                                </JetDropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <JetResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </JetResponsiveNavLink>

                        <JetResponsiveNavLink v-if="is('admin')" :href="route('users.index')" :active="route().current('users.*')">
                            Usuarios
                        </JetResponsiveNavLink>

                        <JetResponsiveNavLink v-if="is('admin')" :href="route('classrooms.index')" :active="route().current('classrooms.*')">
                            Clases
                        </JetResponsiveNavLink>

                        <JetResponsiveNavLink v-if="is('admin')" :href="route('lessons.index')" :active="route().current('lessons.*')">
                            Lecciones
                        </JetResponsiveNavLink>

                        <JetResponsiveNavLink v-if="is('admin')" :href="route('payments.index')" :active="route().current('payments.*')">
                            Pagos
                        </JetResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.user.firstname + ' ' + $page.props.user.lastname }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <JetResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                            <div class="flex items-center gap-2">
                                <CogIcon class="h-4 w-4" aria-hidden="true" />
                                <span class="text-sm">Mi Perfil</span>
                            </div>
                            </JetResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <JetResponsiveNavLink as="button">
                                    <div class="flex items-center gap-2">
                                        <LogoutIcon class="h-4 w-4" aria-hidden="true" />
                                        <span class="text-sm">Cerrar Sesión</span>
                                    </div>
                                </JetResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
</template>
