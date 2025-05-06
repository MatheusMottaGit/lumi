import { useEffect, useState } from 'react';
import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';
import { toast } from 'sonner';
import { ApiResponse } from '@/app/types/main';

const http: AxiosInstance = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
});

type HttpMethod = "GET" | "POST";

interface UseRequestOptions extends AxiosRequestConfig {
  method?: HttpMethod;
}

export function useRequest<T = unknown>(endpoint: string, options?: UseRequestOptions) {
  const [data, setData] = useState<T | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (error) {
      setLoading(false);
      
      toast.error(error, {
        description: "Please try again later.",
      });
    }

    if (data) {
      toast.success(successMessage, {
        description: "You can now proceed to the next step.",
      });
    }
  }, [error, data, successMessage]);

  async function requestFn(overrideOptions?: UseRequestOptions) {
    setLoading(true);
    setError(null);

    const response = await http.request<ApiResponse<T>>({
      url: endpoint,
      method: overrideOptions?.method || options?.method || "GET",
      ...options,
      ...overrideOptions
    });

    if (response.data.success) {
      setData(response.data.data);
      setSuccessMessage(response.data.message);
    } else {
      setError(response.data.message || 'An error occurred.');
    }

    setLoading(false);

    return response.data;
  }

  return {
    data,
    loading,
    error,
    requestFn
  };
}