import Pusher from 'pusher-js'
import Echo from 'laravel-echo'
import { ref, unref } from 'vue'

const echo = ref<Echo | undefined>()

export function useEcho() {
    if (!echo.value)
        echo.value = new Echo({
            broadcaster: 'pusher',
            client: new Pusher(import.meta.env.VITE_APP_PUSHER_APP_KEY, {
                cluster: import.meta.env.VITE_APP_PUSHER_APP_CLUSTER,
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
            }),
        })

    const echoInstance = unref(echo)

    if (!echoInstance) throw new Error('Echo instance is not initialized')

    return {
        echo: echoInstance,
    }
}
