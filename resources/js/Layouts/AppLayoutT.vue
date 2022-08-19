<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationLogo.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import Alert from '@/Components/Alert.vue';
import { Dialog, DialogOverlay, Menu, MenuButton, MenuItem, MenuItems, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CogIcon, LogoutIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    Inertia.post(route('logout'));
};





import {CalendarIcon, ChatIcon, ClipboardListIcon, ClockIcon, HeartIcon, HomeIcon, SearchIcon} from '@heroicons/vue/outline';

const sidebarOpened = ref(false)

let mainNavigation = [
    {href: '/', label: 'Home', icon: HomeIcon},
    {href: '/', label: 'Most recommended', icon: HeartIcon},
    {href: '/', label: 'Most commented', icon: ChatIcon},
]

let libraryNavigation = [
    {href: '/', label: 'Favorites', icon: HeartIcon},
    {href: '/', label: 'Watch later', icon: ClockIcon},
    {href: '/', label: 'History', icon: ClipboardListIcon},
    {href: '/', label: 'Scheduled', icon: CalendarIcon},
]

let following = [
    {href: '/', label: 'Constantin Druc', imageUrl: 'https://pbs.twimg.com/profile_images/1333896976602193922/MtWztkxt_400x400.jpg'},
]

</script>

<template>
<div>
    <Head :title="title" />
    <JetBanner />

    <div class="flex min-h-screen bg-gray-100">

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
                        <div class="px-6 pt-8 pb-4 flex justify-center items-center">
                            <a href="/">
                                <JetApplicationMark class="h-7 w-48"/>
                            </a>
                        </div>

                        <!-- Sidebar Items -->
                        <div class="overflow-y-auto flex-1">
                            <div class="mb-10">
                                <h3 class="mx-6 mb-2 text-xs tracking-widest text-gray-400 uppercase">
                                    Main
                                </h3>

                                <a v-for="(item, index) in mainNavigation"
                                    :href="item.href" :key="index" class="flex items-center px-6 py-2.5 text-gray-500 hover:text-orange-600 group">
                                    <component
                                        :is="item.icon"
                                        class="mr-2 w-5 h-5 text-gray-400 group-hover:text-orange-500"/>
                                    {{ item.label }}
                                </a>
                            </div>
                            <div class="mb-10">
                                <h3 class="mx-6 mb-2 text-xs tracking-widest text-gray-400 uppercase">
                                    Library
                                </h3>

                                <a v-for="(item, index) in libraryNavigation"
                                    :href="item.href" :key="index" class="flex items-center px-6 py-2.5 text-gray-500 hover:text-orange-600 group">
                                    <component
                                        :is="item.icon"
                                        class="mr-2 w-5 h-5 text-gray-400 group-hover:text-orange-500"/>
                                    {{ item.label }}
                                </a>
                            </div>
                            <div class="mb-10">
                                <h3 class="mx-6 mb-2 text-xs tracking-widest text-gray-400 uppercase">
                                    Following
                                </h3>


                                <a v-for="(item, index) in following"
                                    :href="item.href" :key="index" class="flex items-center px-6 py-2.5 text-gray-500 hover:text-orange-600 group">
                                    <img :src="item.imageUrl" alt="" class="mr-2 w-7 h-7 rounded-full">
                                    {{ item.label }}
                                </a>
                            </div>
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

        <!-- Desktop Sidebar -->
        <div class="hidden w-64 bg-gray-50 border-r border-gray-200 md:block">

            <!-- Application Logo -->
            <div class="py-4 px-6 flex justify-center items-center">
                <a href="/">
                    <JetApplicationMark class="w-48 h-9"/>
                </a>
            </div>

            <!-- Sidebar Items -->
            <div class="mb-10">
                <h3 class="mx-6 mb-2 text-xs tracking-widest text-gray-400 uppercase">
                    Main
                </h3>
        
                <a v-for="(item, index) in mainNavigation"
                    :href="item.href" :key="index" class="flex items-center px-6 py-2.5 text-gray-500 hover:text-orange-600 group">
                    <component
                    :is="item.icon"
                    class="mr-2 w-5 h-5 text-gray-400 group-hover:text-orange-500"/>
                    {{ item.label }}
                </a>
            </div>
            <div class="mb-10">
                <h3 class="mx-6 mb-2 text-xs tracking-widest text-gray-400 uppercase">
                    Library
                </h3>
        
                <a v-for="(item, index) in libraryNavigation"
                    :href="item.href" :key="index" class="flex items-center px-6 py-2.5 text-gray-500 hover:text-orange-600 group">
                    <component
                    :is="item.icon"
                    class="mr-2 w-5 h-5 text-gray-400 group-hover:text-orange-500"/>
                    {{ item.label }}
                </a>
            </div>
            <div class="mb-10">
                <h3 class="mx-6 mb-2 text-xs tracking-widest text-gray-400 uppercase">
                    Following
                </h3>
        
        
                <a v-for="(item, index) in following"
                    :href="item.href" :key="index" class="flex items-center px-6 py-2.5 text-gray-500 hover:text-orange-600 group">
                    <img :src="item.imageUrl" alt="" class="mr-2 w-7 h-7 rounded-full">
                    {{ item.label }}
                </a>
            </div>
        </div>

        <!-- Container -->
        <div class="flex-1">

            <!-- Nav -->
            <div class="flex justify-between py-3 px-3 md:px-6 space-x-3 md:space-x-6 bg-gray-50 border-b">
                <div class="flex items-center flex-1">

                    <!-- Open Sidebar Button -->
                    <button
                        @click="sidebarOpened = true"
                        class="mr-3 md:hidden flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:ring-2 hover:ring-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600"
                        type="button" value="Open sidebar">
                        <MenuIcon class="h-6 w-6"/>
                    </button>

                    <form action="" class="w-full max-w-md">
                        <div class="flex relative items-center text-gray-400 focus-within:text-gray-600">
                            <SearchIcon class="absolute ml-3 w-5 h-5 pointer-events-none"/>
                            <input
                            type="text"
                            name="search"
                            placeholder="Search talk"
                            autocomplete="off"
                            aria-label="Search talk"
                            class="py-2 pr-3 pl-10 w-full font-semibold placeholder-gray-500 text-black rounded-2xl border-none ring-2 ring-gray-300 focus:ring-gray-500 focus:ring-2"
                            >
                        </div>
                    </form>
                </div>
                <Menu as="div" class="relative flex-shrink-0">
                    <MenuButton class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        <img class="inline w-10 h-10 rounded-full" src="https://pbs.twimg.com/profile_images/1333896976602193922/MtWztkxt_400x400.jpg" alt="">
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
                            <MenuItem v-slot="{active}">
                                <a href="#" :class="{'bg-gray-100': active}" class="block py-2 px-4 text-sm text-gray-700">My Profile</a>
                            </MenuItem>
                            <MenuItem v-slot="{active, disabled}" disabled>
                                <a href="#" :class="{'bg-gray-100': active, 'opacity-40': disabled}" class="block py-2 px-4 text-sm text-gray-700">Settings</a>
                            </MenuItem>
                            <MenuItem v-slot="{active}">
                                <a href="#" :class="{'bg-gray-100': active}" class="block py-2 px-4 text-sm text-gray-700">Help</a>
                            </MenuItem>
                            <MenuItem v-slot="{active}">
                                <a href="#" :class="{'bg-gray-100': active}" class="block py-2 px-4 text-sm text-gray-700">Log out</a>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>

            <!-- Main Content -->
            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
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
