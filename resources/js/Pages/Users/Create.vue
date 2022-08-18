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
                        <JetLabel for="firstname" value="Nombre" />
                        <JetInput id="firstname" v-model="form.firstname" type="text" class="mt-1 block w-full" :error="form.errors.firstname" required />
                        <JetInputError :message="form.errors.firstname" class="mt-2" />
                    </div>

                    <!-- Lastname -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="lastname" value="Apellido" />
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
                        <JetLabel for="role" value="Rol" />
                        <BaseListbox :options="roles" v-model="form.role" name="role" placeholder="Seleccione un rol" multiple :error="form.errors.role" />
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
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Guardar
                    </PrimaryButton>
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
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import BaseListbox from '@/Components/BaseListbox.vue'

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
    role: [],
})

const submit = () => {
    form.post(route('users.store'), {
        preserveScroll: true,
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>