<script setup>
import { ref, reactive, nextTick } from 'vue';
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import CancelButton from '@/Components/Buttons/CancelButton.vue';
import JetDialogModal from './DialogModal.vue';
import JetInput from './Input.vue';
import JetInputError from './InputError.vue';

const emit = defineEmits(['confirmed']);

defineProps({
    title: {
        type: String,
        default: 'Confirmar Contraseña',
    },
    content: {
        type: String,
        default: 'Por su seguridad, por favor confirme su contraseña para continuar.',
    },
    button: {
        type: String,
        default: 'Confirmar',
    },
});

const confirmingPassword = ref(false);

const form = reactive({
    password: '',
    error: '',
    processing: false,
});

const passwordInput = ref(null);

const startConfirmingPassword = () => {
    axios.get(route('password.confirmation')).then(response => {
        if (response.data.confirmed) {
            emit('confirmed');
        } else {
            confirmingPassword.value = true;

            setTimeout(() => passwordInput.value.focus(), 250);
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios.post(route('password.confirm'), {
        password: form.password,
    }).then(() => {
        form.processing = false;

        closeModal();
        nextTick().then(() => emit('confirmed'));

    }).catch(error => {
        form.processing = false;
        form.error = error.response.data.errors.password[0];
        passwordInput.value.focus();
    });
};

const closeModal = () => {
    confirmingPassword.value = false;
    form.password = '';
    form.error = '';
};
</script>

<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <JetDialogModal :show="confirmingPassword" @close="closeModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                {{ content }}

                <div class="mt-4">
                    <JetInput
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Contraseña"
                        @keyup.enter="confirmPassword"
                    />

                    <JetInputError :message="form.error" class="mt-2" />
                </div>
            </template>

            <template #footer>
                <CancelButton type="button" class="mr-4" @click="closeModal">
                    Cancelar
                </CancelButton>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="confirmPassword">
                    {{ button }}
                </PrimaryButton>
            </template>
        </JetDialogModal>
    </span>
</template>
