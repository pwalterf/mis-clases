<template>
    <AppLayout title="Usuarios">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'users.index'">Usuarios</BreadcrumbLink>
                </template>

                <template #static>
                    Modificar Usuario
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <FormSection @submitted="submit">
                <template #form>
                    <!-- Firstname -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="firstname" value="Firstname" />
                        <JetInput id="firstname" v-model="form.firstname" type="text" class="mt-1 block w-full" required autofocus autocomplete="firstname"/>
                        <JetInputError :message="form.errors.firstname" class="mt-2" />
                    </div>

                    <!-- Lastname -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="lastname" value="Lastname" />
                        <JetInput id="lastname" v-model="form.lastname" type="text" class="mt-1 block w-full" required autocomplete="lastname"/>
                        <JetInputError :message="form.errors.lastname" class="mt-2" />
                    </div>

                    <!-- DNI -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="dni" value="DNI" />
                        <JetInput id="dni" v-model="form.dni" type="text" class="mt-1 block w-full" required autocomplete="dni"/>
                        <JetInputError :message="form.errors.dni" class="mt-2" />
                    </div>

                    <!-- Birthdate -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="birthdate" value="Birthdate" />
                        <JetInput id="birthdate" v-model="form.birthdate" type="date" class="mt-1 block w-full" required autocomplete="birthdate"/>
                        <JetInputError :message="form.errors.birthdate" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="col-span-6 md:col-span-3">
                        <JetLabel for="email" value="Email" />
                        <JetInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autocomplete="email"/>
                        <JetInputError :message="form.errors.email" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <Button class="bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700"
                        :class="{ 'opacity-25': form.processing}" :disabled="form.processing || !form.isDirty"
                    >
                        <span v-text="form.isDirty ? 'Guardar Cambios' : 'Sin Cambios'"></span>
                    </Button>

                    <div v-if="$page.props.user.id !== userData.id">
                        <Button v-if="userData.deleted_at" type="button" class="bg-green-700 hover:bg-green-800 focus:bg-green-900 focus:ring-green-600 active:bg-green-900" :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal">
                            Restaurar
                        </Button>

                        <Button v-else type="button" class="bg-red-600 hover:bg-red-700 focus:bg-red-800 focus:ring-red-500 active:bg-red-800" :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="confirmModal">
                            Eliminar
                        </Button>
                    </div>
                </template>
            </FormSection>
        </div>

        <JetDialogModal :show="confirming" @close="closeModal">
            <template #title>
                <span v-text="props.userData.deleted_at ? 'Restaurar' : 'Eliminar'"></span> Usuario
            </template>

            <template #content>
                ¿Esta seguro que desea realizar esta acción?
            </template>

            <template #footer>
                <Button class="bg-gray-500 hover:bg-gray-600 focus:bg-gray-700 focus:ring-gray-400 active:bg-gray-700" @click="closeModal">
                    Cancelar
                </Button>

                <Button class="ml-4 bg-pink-500 hover:bg-pink-600 focus:bg-pink-700 focus:ring-pink-400 active:bg-pink-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="confirmingAction">
                    Confirmar
                </Button>
            </template>
        </JetDialogModal>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import BreadcrumbLink from '@/Components/BreadcrumbLink.vue'
import FormSection from '@/Components/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import Button from '@/Components/Button.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'

const props = defineProps({
    userData: Object
})

const confirming = ref(false)

const form = useForm({
    _method: 'PUT',
    firstname: props.userData.firstname,
    lastname: props.userData.lastname,
    dni: props.userData.dni,
    birthdate: props.userData.birthdate,
    email: props.userData.email,
    //password: '',
    //password_confirmation: '',
    //role: userData.role,
})

const confirmModal = () => {
    confirming.value = true
}

const closeModal = () => {
    confirming.value = false
}

const submit = () => {
    form.put(route('users.update', props.userData), {
        preserveScroll: true,
        //onFinish: () => form.reset('password', 'password_confirmation'),
    })
}

const confirmingAction = () => {
    if (props.userData.deleted_at) {
        form.put(route('users.restore', props.userData), {
            preserveScroll: true,
            onSuccess: () => closeModal()
        })
    } else {
        form.delete(route('users.destroy', props.userData), {
            preserveScroll: true,
            onSuccess: () => closeModal()
        })
    }
}
</script>