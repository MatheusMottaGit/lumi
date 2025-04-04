import { useState } from 'react';
import axios, { AxiosError, AxiosInstance, AxiosRequestConfig } from 'axios';

const http: AxiosInstance = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
});

type HttpMethod = "GET" | "POST";

interface UseRequestOptions extends AxiosRequestConfig {
  method?: HttpMethod;
}

export function useRequest<T = unknown>(endpoint: string, options?: UseRequestOptions) {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  async function requestFn(overrideOptions?: UseRequestOptions) {
    setLoading(true);
    setError(null);

    try {
      const response = await http.request<T>({
        url: endpoint,
        method: overrideOptions?.method || options?.method || "GET",
        ...options,
        ...overrideOptions
      });

      setData(response.data);
      return response.data;

    } catch (error) {
      const axiosError = error as AxiosError<{ error: string }>;
    
      if (axiosError.response?.data?.error) {
        setError(axiosError.response.data.error);
      } else {
        setError(axiosError.message);
      }
    }
     finally {
      setLoading(false);
    }
  }

  return {
    data,
    loading,
    error,
    requestFn
  }
}
