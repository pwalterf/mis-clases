<script setup>
import { computed, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import Alert from '@/Components/Alert.vue';
import { HomeIcon, UserIcon, BookOpenIcon, AcademicCapIcon, CashIcon, CogIcon, LogoutIcon } from '@heroicons/vue/outline'

defineProps({
    title: String,
});

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
            <div class="drawer">
                <input id="open-drawer" type="checkbox" class="drawer-toggle" /> 
                <div class="drawer-content flex flex-col">
                    <!-- Navbar -->
                    <nav class="bg-white shadow sticky top-0 inset-0 z-40">
                        <div class="max-w-7xl mx-auto px-2 md:px-6 lg:px-8">
                            <div class="flex justify-between h-16">
                                <div class="flex">
                                    <!-- Logo -->
                                    <div class="shrink-0 flex items-center">
                                        <div class="flex items-center md:hidden mr-4">
                                            <label for="open-drawer" class="btn btn-square btn-ghost">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                            </label>
                                        </div>
                                        <Link :href="route('dashboard')" class="flex items-center">
                                            <JetApplicationMark class="block h-9 w-auto" />
                                            <span class="font-semibold ml-4">Mis Clases</span>
                                        </Link>
                                    </div>

                                    <!-- Navigation Links -->
                                    <div class="hidden space-x-6 md:-my-px md:ml-10 md:flex">
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
                                    <!-- Settings Dropdown -->
                                    <div class="ml-3 relative mr-1 sm:mr-3">
                                        <JetDropdown align="right" width="48">
                                            <template #trigger>
                                                <span class="inline-flex rounded-md">
                                                    <button type="button" class="md:inline-flex hidden items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
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

                                                    <button class="md:hidden flex border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                        <div class="avatar placeholder">
                                                            <div class="bg-neutral text-neutral-content rounded-full w-10">
                                                                <span>{{ avatar }}</span>
                                                            </div>
                                                        </div>
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

                                                <JetDropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                                    Tokens API
                                                </JetDropdownLink>

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
                            </div>
                        </div>
                    </nav>
                    <!-- Page content here -->
                    <!-- Page Heading -->
                    <header v-if="$slots.header" class="bg-white shadow min-h-[64px]">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            <slot name="header" />
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="pb-8">
                        <div class="py-4">
                            <Alert />
                        </div>
                        
                        <slot />
                    </main>
                </div> 

                <div class="drawer-side">
                    <label for="open-drawer" class="drawer-overlay"></label> 
                    <div class="menu p-4 overflow-y-auto w-64 bg-white">
                        <!-- Sidebar content here -->
                        <nav class="space-y-2">
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

                            <div class="divider"></div>

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
    </div>
</template>
