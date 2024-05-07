/// <reference types="vite/client" />

interface ImportMetaEnv {
    readonly VITE_APP_TITLE: string
    readonly VITE_APP_NAME: string
    readonly VITE_APP_PUSHER_APP_KEY: string
    readonly VITE_APP_PUSHER_APP_CLUSTER: string
}

interface ImportMeta {
    readonly env: ImportMetaEnv
}
