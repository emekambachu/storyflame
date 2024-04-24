import { AxiosResponse } from 'axios'

export interface SuccessResponse<T> {
    message: string
    data: T
    type: 'success'
}

export interface ErrorResponse {
    message: string
    details: string | null
    type: 'error'
}
