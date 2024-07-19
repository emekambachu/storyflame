import axios, { AxiosRequestConfig } from 'axios'
import { ApiSuccessResponse, SuccessResponse } from '@/types/responses'
import { useMockStore } from '@/stores/mocks'

function submit<T>(
    method: string,
    url: string,
    data: AxiosRequestConfig | null = null
): Promise<ApiSuccessResponse<T>> {
    const mockStore = useMockStore();

    if (mockStore.isActive(method, url)) {
        const response = mockStore.getMockResponse(method, url);
        console.log('mock response', response);
        return Promise.resolve({
            response: {
                data: response,
            } as any,
            data: response,
            error: null,
        });
    }

    console.log('real request', data);

    return axios<SuccessResponse<T>>({
        method,
        url,
        ...(data || {}),
    })
        .then((response) => {
            return {
                response,
                data: response.data.data,
                error: null,
            };
        })
        .catch((error) => {
            return Promise.reject({
                response: error.response,
                data: null,
                error: error.response?.data || error.message,
            });
        });
}

function get<T>(
    url: string,
    config: AxiosRequestConfig | null = null
): Promise<ApiSuccessResponse<T>> {
    return submit<T>('GET', url, config)
}

function post<T>(
    url: string,
    data: any = null,
    config: AxiosRequestConfig | null = null
): Promise<ApiSuccessResponse<T>> {
    console.log(data ? { ...config, data } : config)
    return submit<T>('POST', url, data ? { ...config, data } : config)
}

function put<T>(
    url: string,
    data: any = null,
    config: AxiosRequestConfig | null = null
): Promise<ApiSuccessResponse<T>> {
    return submit<T>('PUT', url, data ? { ...config, data } : config)
}

function patch<T>(
    url: string,
    data: any = null,
    config: AxiosRequestConfig | null = null
): Promise<ApiSuccessResponse<T>> {
    return submit<T>('PATCH', url, data ? { ...config, data } : config)
}

function del<T>(
    url: string,
    config: AxiosRequestConfig | null = null
): Promise<ApiSuccessResponse<T>> {
    return submit<T>('DELETE', url, config)
}

const api = {
    submit,
    get,
    post,
    put,
    patch,
    delete: del,
}

export default api
