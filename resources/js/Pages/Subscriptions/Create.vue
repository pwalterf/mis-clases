<template>
    <AppLayout title="Clases">
        <template #header>
            <Breadcrumb>
                <template #links>
                    <BreadcrumbLink :href="'dashboard'">Inicio</BreadcrumbLink>
                    <BreadcrumbLink :href="'classrooms.index'">Suscripciones</BreadcrumbLink>
                </template>

                <template #static>
                    Nueva Suscripción
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <FormSection @submitted="submit">
                <template #form>
                    <!-- Price_hr -->
                    <div class="col-span-6">
                        <JetLabel for="price_hr" value="Precio por Hora" />
                        <JetInput id="price_hr" v-model="form.price_hr" type="text" class="mt-1 block w-full" :error="form.errors.price_hr" required />
                        <JetInputError :message="form.errors.price_hr" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Guardar
                    </JetButton>
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
import JetButton from '@/Jetstream/Button.vue'
import JetInputError from '@/Jetstream/InputError.vue'

const form = useForm({
    price_hr: '',
})

const submit = () => {
    form.post(route('subscription.store'), {
        preserveScroll: true,
    })
}
</script>