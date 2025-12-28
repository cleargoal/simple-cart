<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3'
import { useToast } from '@/composables/useToast'
import { onMounted, onUnmounted } from 'vue'

const page = usePage()
const { success, error } = useToast()

const checkFlashMessages = () => {
    const successMessage = page.props.success
    const errorMessage = page.props.error

    if (successMessage && typeof successMessage === 'string') {
        success(successMessage)
    }

    if (errorMessage && typeof errorMessage === 'string') {
        error(errorMessage)
    }
}

// Listen to Inertia navigation events
const removeListener = router.on('success', () => {
    // Small delay to ensure props are updated
    setTimeout(checkFlashMessages, 10)
})

onMounted(() => {
    // Check on initial mount
    checkFlashMessages()
})

onUnmounted(() => {
    removeListener()
})
</script>

<template>
    <!-- This component has no template, it only handles flash messages -->
</template>
