import { ref } from 'vue'

export type ToastType = 'success' | 'error' | 'info'

export interface Toast {
    id: string
    title?: string
    description?: string
    type: ToastType
    duration?: number
}

const toasts = ref<Toast[]>([])

let count = 0

export function useToast() {
    const show = (toast: Omit<Toast, 'id'>) => {
        const id = `toast-${++count}`
        const newToast: Toast = {
            id,
            duration: 8000,
            ...toast,
        }

        toasts.value.push(newToast)

        if (newToast.duration) {
            setTimeout(() => {
                dismiss(id)
            }, newToast.duration)
        }

        return id
    }

    const dismiss = (id: string) => {
        const index = toasts.value.findIndex(t => t.id === id)
        if (index > -1) {
            toasts.value.splice(index, 1)
        }
    }

    const success = (title: string, description?: string) => {
        return show({ type: 'success', title, description })
    }

    const error = (title: string, description?: string) => {
        return show({ type: 'error', title, description })
    }

    const info = (title: string, description?: string) => {
        return show({ type: 'info', title, description })
    }

    return {
        toasts,
        show,
        dismiss,
        success,
        error,
        info,
    }
}
