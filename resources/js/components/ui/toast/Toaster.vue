<script setup lang="ts">
import { ToastProvider, ToastRoot, ToastTitle, ToastDescription, ToastClose, ToastViewport } from 'reka-ui'
import { X, CheckCircle2, XCircle, Info } from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'
import { computed } from 'vue'

const { toasts, dismiss } = useToast()

const getIcon = (type: string) => {
    switch (type) {
        case 'success':
            return CheckCircle2
        case 'error':
            return XCircle
        default:
            return Info
    }
}

const getStyles = (type: string) => {
    switch (type) {
        case 'success':
            return 'border-green-500 bg-green-50 dark:bg-green-950 text-green-900 dark:text-green-100'
        case 'error':
            return 'border-red-500 bg-red-50 dark:bg-red-950 text-red-900 dark:text-red-100'
        default:
            return 'border-blue-500 bg-blue-50 dark:bg-blue-950 text-blue-900 dark:text-blue-100'
    }
}
</script>

<template>
    <ToastProvider>
        <div class="fixed top-0 right-0 z-[60] flex max-h-screen w-full flex-col gap-2 p-4 sm:top-0 sm:right-0 md:max-w-[420px]">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="relative pointer-events-auto flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-4 pr-8 shadow-lg"
                :class="getStyles(toast.type)"
            >
                <div class="flex gap-3">
                    <component :is="getIcon(toast.type)" class="h-5 w-5 flex-shrink-0" />
                    <div class="grid gap-1">
                        <div v-if="toast.title" class="text-sm font-semibold">
                            {{ toast.title }}
                        </div>
                        <div v-if="toast.description" class="text-sm opacity-90">
                            {{ toast.description }}
                        </div>
                    </div>
                </div>
                <button
                    @click="dismiss(toast.id)"
                    class="absolute right-1 top-1 rounded-md p-1 hover:bg-black/10"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>
        <ToastViewport />
    </ToastProvider>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
