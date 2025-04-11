import { useEffect, useState } from 'react';
import axios, { AxiosError, AxiosInstance, AxiosRequestConfig } from 'axios';
import { toast } from 'sonner';

const http: AxiosInstance = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
});

type HttpMethod = "GET" | "POST";

interface UseRequestOptions extends AxiosRequestConfig {
  method?: HttpMethod;
}

export interface ApiResponse<T> {
  message: string;
  data: T;
}

export function useRequest<T = unknown>(endpoint: string, options?: UseRequestOptions) {
  const [data, setData] = useState<T | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  async function requestFn(overrideOptions?: UseRequestOptions) {
    setLoading(true);
    setError(null);

    try {
      const response = await http.request<ApiResponse<T>>({
        url: endpoint,
        method: overrideOptions?.method || options?.method || "GET",
        ...options,
        ...overrideOptions
      });

      setData(response.data.data);
      setSuccessMessage(response.data.message);
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

  useEffect(() => {
    if (error) {
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

  return {
    data,
    loading,
    error,
    requestFn
  }
}
