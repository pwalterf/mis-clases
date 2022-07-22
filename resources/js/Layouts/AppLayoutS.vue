<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import Alert from '@/Components/Alert.vue';
import { HomeIcon, UserIcon, BookOpenIcon, AcademicCapIcon, CashIcon, CogIcon, LogoutIcon } from '@heroicons/vue/outline'

defineProps({
    title: String,
});

const showingNavigationSidebar = ref(false);

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <JetBanner />

        <div class="min-h-screen bg-gray-100">
            <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
            <div class="md:hidden" v-show="showingNavigationSidebar">
                <div class="fixed inset-0 flex z-40">
                    <!--
                        Off-canvas menu overlay, show/hide based on off-canvas menu state.

                        Entering: "transition-opacity ease-linear duration-300"
                        From: "opacity-0"
                        To: "opacity-100"
                        Leaving: "transition-opacity ease-linear duration-300"
                        From: "opacity-100"
                        To: "opacity-0"
                    -->
                    <div class="fixed inset-0" @click="showingNavigationSidebar = false">
                        <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                    </div>

                    <!--
                        Off-canvas menu, show/hide based on off-canvas menu state.

                        Entering: "transition ease-in-out duration-300 transform"
                        From: "-translate-x-full"
                        To: "translate-x-0"
                        Leaving: "transition ease-in-out duration-300 transform"
                        From: "translate-x-0"
                        To: "-translate-x-full"
                    -->
                    <div class="relative flex-1 flex flex-col max-w-[16em] py-4 bg-white">
                        <div class="flex-shrink-0 flex items-center justify-between px-3">
                            <div class="inline-flex justify-center w-full">
                                <JetApplicationMark class="h-9" />
                            </div>

                            <button v-on:click="showingNavigationSidebar = false" class="h-5 w-5" aria-label="Close sidebar">
                                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-8 flex-1 h-0 overflow-y-auto">
                            <nav class="space-y-1">
                                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    <HomeIcon class="h-5 w-5" aria-hidden="true" />
                                    Dashboard
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="is('admin')" :href="route('users.index')" :active="route().current('users.*')">
                                    <UserIcon class="h-5 w-5" aria-hidden="true" />
                                    Usuarios
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="is('admin')" :href="route('classrooms.index')" :active="route().current('classrooms.*')">
                                    <AcademicCapIcon class="h-5 w-5" aria-hidden="true" />
                                    Clases
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="is('admin')" :href="route('lessons.index')" :active="route().current('lessons.*')">
                                    <BookOpenIcon class="h-5 w-5" aria-hidden="true" />
                                    Lecciones
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="is('admin')" :href="route('payments.index')" :active="route().current('payments.*')">
                                    <CashIcon class="h-5 w-5" aria-hidden="true" />
                                    Pagos
                                </ResponsiveNavLink>

                                <div class="border-b"></div>

                                <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                    <CogIcon class="h-4 w-4" aria-hidden="true" />
                                    <span class="text-sm">Mi Perfil</span>
                                </ResponsiveNavLink>

                                <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink as="button">
                                        <LogoutIcon class="h-4 w-4" aria-hidden="true" />
                                        <span class="text-sm">Cerrar Sesión</span>
                                    </ResponsiveNavLink>
                                </form>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <JetApplicationMark class="block h-9 w-auto" />
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
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
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
                                            Mi Perfil
                                        </JetDropdownLink>

                                        <JetDropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                            Tokens API
                                        </JetDropdownLink>

                                        <div class="border-t border-gray-100" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <JetDropdownLink as="button">
                                                Cerrar Sesión
                                            </JetDropdownLink>
                                        </form>
                                    </template>
                                </JetDropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition" @click="showingNavigationSidebar = ! showingNavigationSidebar">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        class="inline-flex"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                </svg>
                            </button>
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
