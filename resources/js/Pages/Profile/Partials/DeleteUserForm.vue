<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import JetDialogModal from '@/Jetstream/DialogModal.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import DangerButton from '@/Components/Buttons/DangerButton.vue';
import CancelButton from '@/Components/Buttons/CancelButton.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <JetActionSection>
        <template #title>
            Eliminar Cuenta
        </template>

        <template #description>
            Eliminar tu cuenta permanentemente.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Una vez que tu cuenta es eliminada, todos los datos seran eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que quieras mantener.
            </div>

            <div class="mt-5">
                <DangerButton type="button" @click="confirmUserDeletion">
                    Eliminar Cuenta
                </DangerButton>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <JetDialogModal :show="confirmingUserDeletion" @close="closeModal">
                <template #title>
                    Eliminar Cuenta
                </template>

                <template #content>
                    ¿Estas seguero que deseas eliminar tu cuenta? 
                    Are you sure you want to delete your account? Una vez que tu cuenta es eliminada, todos los datos seran eliminados permanentemente. Por favor ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.

                    <div class="mt-4">
                        <JetInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="Password"
                            @keyup.enter="deleteUser"
                        />

                        <JetInputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <CancelButton type="button" class="mr-4" @click="closeModal">
                        Cancelar
                    </CancelButton>

                    <DangerButton type="button" :class="{ 'opacity-25': form.processing}" :disabled="form.processing" @click="deleteUser">
                        Eliminar Cuenta
                    </DangerButton>
                </template>
            </JetDialogModal>
        </template>
    </JetActionSection>
</template>
