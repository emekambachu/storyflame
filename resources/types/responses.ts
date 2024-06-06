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

export type Response<T> = SuccessResponse<T> | ErrorResponse

export type ApiSuccessResponse<T> = {
    response: AxiosResponse<Response<T>>
    data: T
    error: ErrorResponse | null
}
