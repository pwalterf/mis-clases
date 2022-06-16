<script setup>
defineProps({
    message: [String, Array]
});
</script>

<template>
    <div v-show="message">
        <p v-for="msg in message" :key="msg" class="text-sm text-red-600">
            {{ msg }}
        </p>
    </div>
</template>
