<template>
    <AppLayout title="Usuarios">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'users.index'">Usuarios</BreadcrumbLink>
                </template>

                <template #static>
                    Nuevo Usuario
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <FormSection @submitted="submit">
                <template #form>
                    <!-- Firstname -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="firstname" value="Firstname" />
                        <JetInput id="firstname" v-model="form.firstname" type="text" class="mt-1 block w-full" :error="form.errors.firstname" required />
                        <JetInputError :message="form.errors.firstname" class="mt-2" />
                    </div>

                    <!-- Lastname -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="lastname" value="Lastname" />
                        <JetInput id="lastname" v-model="form.lastname" type="text" class="mt-1 block w-full" :error="form.errors.lastname" required />
                        <JetInputError :message="form.errors.lastname" class="mt-2" />
                    </div>

                    <!-- DNI -->
                    <!-- <div class="col-span-6 md:col-span-3">
                        <JetLabel for="dni" value="DNI" />
                        <JetInput id="dni" v-model="form.dni" type="text" class="mt-1 block w-full" :error="form.errors.dni" required />
                        <JetInputError :message="form.errors.dni" class="mt-2" />
                    </div> -->

                    <!-- Birthdate -->
                    <!-- <div class="col-span-6 md:col-span-3">
                        <JetLabel for="birthdate" value="Birthdate" />
                        <JetInput id="birthdate" v-model="form.birthdate" type="date" class="mt-1 block w-full" :error="form.errors.birthdate" required />
                        <JetInputError :message="form.errors.birthdate" class="mt-2" />
                    </div> -->

                    <!-- Email Address -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="email" value="Email" />
                        <JetInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" :error="form.errors.email" required />
                        <JetInputError :message="form.errors.email" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="role" value="Role" />
                        <Listbox v-model="form.role" name="role">
                            <div class="relative mt-1">
                                <ListboxButton :class="{'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.role}" class="border mt-1 block w-full cursor-default py-2 pl-3 pr-10 text-left border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <span class="block truncate capitalize">{{ form.role ? form.role : 'Seleccione Rol...' }}</span>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                        <SelectorIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                    </span>
                                </ListboxButton>

                                <transition
                                    leave-active-class="transition duration-200 ease-in"
                                    leave-from-class="transform opacity-100"
                                    leave-to-class="transform opacity-0"
                                    enter-active-class="transition duration-200 ease-out"
                                    enter-from-class="transform scale-y-75 -translate-y-5"
                                    enter-to-class="transform scale-y-100 translate-y-0"
                                >
                                    <ListboxOptions class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                        <ListboxOption as="template" :value="null" disabled>
                                            <li class="text-gray-900 relative cursor-default select-none py-2 pl-10 pr-4"><span class="opacity-75">Seleccione Rol...</span></li>
                                        </ListboxOption>
                                        <ListboxOption v-slot="{ active, selected }" v-for="role in roles" :key="role" :value="role" as="template">
                                        <li :class="[active ? 'bg-indigo-100 text-indigo-900' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-10 pr-4']">
                                            <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate capitalize']">
                                                {{ role }}
                                            </span>
                                            <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600">
                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </li>
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>
                        <JetInputError :message="form.errors.role" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <!-- <div class="col-span-6 md:col-span-3">
                        <JetLabel for="password" value="Password" />
                        <JetInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" :error="form.errors.password" required />
                        <JetInputError :message="form.errors.password" class="mt-2" />
                    </div> -->

                    <!-- Password Confirmation -->
                    <!-- <div class="col-span-6 md:col-span-3">
                        <JetLabel for="password_confirmation" value="Confirm Password" />
                        <JetInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" :error="form.errors.password_confirmation" required />
                        <JetInputError :message="form.errors.password_confirmation" class="mt-2" />
                    </div> -->
                </template>

                <template #actions>
                    <Button class="bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Guardar
                        </Button>
                </template>
            </FormSection>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import FormSection from '@/Components/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Button from '@/Components/Button.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import { CheckIcon, SelectorIcon } from '@heroicons/vue/solid'

const props = defineProps({
    roles: Object
})

const form = useForm({
    firstname: '',
    lastname: '',
    //dni: '',
    //birthdate: '',
    email: '',
    //password: '',
    //password_confirmation: '',
    role: null,
})

const submit = () => {
    form.post(route('users.store'), {
        preserveScroll: true,
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>