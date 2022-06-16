<template>
    <transition
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform opacity-100"
        leave-to-class="transform opacity-0"
    >
        <div v-if="show && message" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm rounded-md" :class="alert[style]" role="alert" @mouseover="twn.pause()" @mouseleave="twn.resume()">
                <div class="flex items-center justify-between flex-wrap p-4 pb-3">
                    <div>
                        <span class="font-bold capitalize">{{ style }}</span> {{ message }}
                    </div>

                    <div class="shrink-0 sm:ml-3">
                        <button type="button" class="-mr-1 flex px-2 rounded-md focus:outline-none sm:-mr-2 transition" aria-label="Dismiss" @click.prevent="show = false">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex w-full h-1 rounded-b-md overflow-hidden">
                    <div id="progressbar" ref="progressbar" class="flex flex-col justify-center overflow-hidden" :class="progress[style]" role="progressbar"></div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import gsap from 'gsap'

const progressbar = ref(null)
const twn = ref(null)
const show = ref(false)
const style = computed(() => usePage().props.value.flash?.alert.style || 'exitoso')
const message = computed(() => usePage().props.value.flash?.alert.message)

const alert = {
    exitoso: 'bg-green-100 border border-green-200 text-green-600',
    error: 'bg-red-100 border border-red-200 text-red-600',
    advertencia: 'bg-orange-100 border border-orange-200 text-orange-600'
}

const progress = {
    exitoso: 'bg-green-500',
    error: 'bg-red-500',
    advertencia: 'bg-orange-500'
}

function trueShow() {
    show.value = true
}

watch(message, async () => {
    if (message.value) {
        await trueShow()

        if (progressbar.value) {
            twn.value = gsap.fromTo("#progressbar", {width: '100%'}, { duration: 5, ease: "none", width: '0%', onComplete: function() {show.value = false} })
        }
    }
}, {deep: true})
</script>