<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    firstname: props.user.firstname,
    lastname: props.user.lastname,
    dni: props.user.dni,
    birthdate: props.user.birthdate,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    Inertia.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <JetFormSection @submitted="updateProfileInformation">
        <template #title>
            Información de Perfil
        </template>

        <template #description>
            Actualice la información de su cuenta y la dirección de correo electrónico.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                >

                <JetLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="! photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <JetSecondaryButton class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    Seleccione una Nueva Foto
                </JetSecondaryButton>

                <JetSecondaryButton
                    v-if="user.profile_photo_path"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                >
                    Eliminar Foto
                </JetSecondaryButton>

                <JetInputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Firstname -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="firstname" value="Firstname" />
                <JetInput
                    id="firstname"
                    v-model="form.firstname"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="firstname"
                />
                <JetInputError :message="form.errors.firstname" class="mt-2" />
            </div>

            <!-- Lastname -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="lastname" value="Lastname" />
                <JetInput
                    id="lastname"
                    v-model="form.lastname"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="lastname"
                />
                <JetInputError :message="form.errors.lastname" class="mt-2" />
            </div>

            <!-- DNI -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="dni" value="DNI" />
                <JetInput
                    id="dni"
                    v-model="form.dni"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="dni"
                />
                <JetInputError :message="form.errors.dni" class="mt-2" />
            </div>

            <!-- Birthdate -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="birthdate" value="Birthdate" />
                <JetInput
                    id="birthdate"
                    v-model="form.birthdate"
                    type="date"
                    class="mt-1 block w-full"
                    autocomplete="birthdate"
                />
                <JetInputError :message="form.errors.birthdate" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                />
                <JetInputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Su dirección de correo electrónico no está verificada.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-gray-600 hover:text-gray-900"
                            @click.prevent="sendEmailVerification"
                        >
                            Haga clic aquí para reenviar el correo de verificación.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                Guardado.
            </JetActionMessage>

            <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Guardar
            </JetButton>
        </template>
    </JetFormSection>
</template>
