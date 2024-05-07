export type UserData = {
    media: any[]
    bio: string
    genre_focus: string[]
    themes: string[]
    writing_medium: string[]
    characters: string[]
    audience: string[]
}

export default interface User {
    id: number
    name: string
    email: string
    password: string | undefined
    onboarded: boolean | undefined
    data: UserData | undefined
}
