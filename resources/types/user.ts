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
    first_name: string
    last_name: string
    bio: string
    writing_goals: string
    password: string | undefined
    onboarded: boolean | undefined
    data: UserData | undefined
    completed_achievements: number
    progress_achievements: number
    next_achievements: number
    email_verified_at?: string
    referral_code?: string
    referred_by_code?: string
}
