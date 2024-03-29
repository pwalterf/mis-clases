<script setup>
import { ref, computed, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import JetActionSection from '@/Jetstream/ActionSection.vue';
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue';
import DangerButton from '@/Components/Buttons/DangerButton.vue';
import CancelButton from '@/Components/Buttons/CancelButton.vue';
import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';

const props = defineProps({
    requiresConfirmation: Boolean,
});

const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => ! enabling.value && usePage().props.value.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    Inertia.post('/user/two-factor-authentication', {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get('/user/two-factor-qr-code').then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get('/user/two-factor-secret-key').then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get('/user/two-factor-recovery-codes').then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post('/user/confirmed-two-factor-authentication', {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post('/user/two-factor-recovery-codes')
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    Inertia.delete('/user/two-factor-authentication', {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <JetActionSection>
        <template #title>
            Doble Factor de Autenticación
        </template>

        <template #description>
            Agrega seguridad adicional a tu cuenta usando un doble factor de autenticación.
        </template>

        <template #content>
            <h3 v-if="twoFactorEnabled && ! confirming" class="text-lg font-medium text-gray-900">
                Tienes habilitado el doble factor de autenticación.
            </h3>

            <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-gray-900">
                Termine de habilitar el doble factor de autenticación.
            </h3>

            <h3 v-else class="text-lg font-medium text-gray-900">
                No tienes habilitado el doble factor de autenticación.
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600">
                <p>
                    Cuando el doble factor de autenticación esté habilitado, le pediremos un token aleatorio seguro durante la autenticación. Puedes obtener este token desde la aplicación Autenticador de Google de su teléfono.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p v-if="confirming" class="font-semibold">
                            Para terminar de habilitar el doble factor de autenticación, escanee el siguiente código QR usando la aplicación de autenticación de su teléfono o ingrese la clave de configuración y proporcione el código OTP generado.
                        </p>

                        <p v-else>
                            El doble factor de autenticación ahora está habilitado. Escanee el siguiente código QR usando la aplicación de autenticación de su teléfono o ingrese la clave de configuración.
                        </p>
                    </div>

                    <div class="mt-4" v-html="qrCode" />

                    <div class="mt-4 max-w-xl text-sm text-gray-600" v-if="setupKey">
                        <p class="font-semibold">
                            Clave de configuración: <span v-html="setupKey"></span>
                        </p>
                    </div>

                    <div v-if="confirming" class="mt-4">
                        <JetLabel for="code" value="Code" />

                        <JetInput
                            id="code"
                            v-model="confirmationForm.code"
                            type="text"
                            name="code"
                            class="block mt-1 w-1/2"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                            @keyup.enter="confirmTwoFactorAuthentication"
                        />

                        <JetInputError :message="confirmationForm.errors.code" class="mt-2" />
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && ! confirming">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Guarde estos códigos de recuperación en un administrador de contraseñas seguro. Se pueden utilizar para recuperar el acceso a su cuenta si pierde su dispositivo de doble factor de autenticación.
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="! twoFactorEnabled">
                    <JetConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <PrimaryButton type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling">
                            Habilitar
                        </PrimaryButton>
                    </JetConfirmsPassword>
                </div>

                <div v-else>
                    <JetConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <PrimaryButton v-if="confirming" class="mr-3" type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling">
                            Confirmar
                        </PrimaryButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton v-if="recoveryCodes.length > 0 && ! confirming" type="button" class="mr-3">
                            Regenerar códigos de recuperación
                        </SecondaryButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton v-if="recoveryCodes.length === 0 && ! confirming" type="button" class="mr-3">
                            Mostrar códigos de recuperación
                        </SecondaryButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <CancelButton v-if="confirming" type="button" class="mr-3" :class="{ 'opacity-25': disabling }" :disabled="disabling">
                            Cancelar
                        </CancelButton>
                    </JetConfirmsPassword>

                    <JetConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <DangerButton v-if="! confirming" type="button" class="mt-2" :class="{ 'opacity-25': disabling }" :disabled="disabling">
                            Deshabilitar
                        </DangerButton>
                    </JetConfirmsPassword>
                </div>
            </div>
        </template>
    </JetActionSection>
</template>
