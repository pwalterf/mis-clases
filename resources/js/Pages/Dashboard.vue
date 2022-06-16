<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue'
import Welcome from '@/Jetstream/Welcome.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <Breadcrumb>
                <template #static>
                    Home
                </template>
            </Breadcrumb>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <Welcome />
            </div>
        </div>
    </AppLayout>
</template>
