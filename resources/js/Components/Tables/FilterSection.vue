<template>
    <section class="flex items-center justify-between mb-2">
        <template v-if="filters">
            <div class="lg:w-48">
                <JetSelect v-model="filterOptions" class="w-full">
                    <template #options>
                        <option value="all">Todos</option>
                        <option v-for="filter in filters" :value="filter.value">{{ filter.label }}</option>
                    </template>
                </JetSelect>
            </div>
        </template>

        <template v-if="search">
            <div class="lg:w-64">
                <JetInput type="search" v-model="searchValue" class="w-full" placeholder="Buscar..." />
            </div>
        </template>
    </section>
</template>

<script setup>
import JetSelect from '@/Jetstream/Select.vue'
import JetInput from '@/Jetstream/Input.vue'

const props = defineProps({
    filters: Array,
    search: Boolean,
})

const filterOptions = ref()
const searchValue = ref()
</script>