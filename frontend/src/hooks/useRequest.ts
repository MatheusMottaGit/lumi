import { useState, useCallback } from 'react';
import { AxiosRequestConfig } from 'axios';
import { http } from '../lib/axios';

interface UseRequestResult<T> {
  data: T | null;
  error: string | null;
  loading: boolean;
  requestFn: (url: string, config?: AxiosRequestConfig) => Promise<T | undefined>;
}

export function useRequest<T = unknown>(): UseRequestResult<T> {
  const [data, setData] = useState<T | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState<boolean>(false);

  async function requestFn(url: string, config: AxiosRequestConfig = {}) {
    setLoading(true);
    setError(null);

    try {
      const response = await http.request<T>({
        url,
        method: config.method || 'GET',
        ...config,
      });

      setData(response.data);
      return response.data;
    } catch (err: any) {
      setError(err.response?.data?.message || err.message || 'Something went wrong...');
    } finally {
      setLoading(false);
    }
  }

  return { 
    data, 
    error, 
    loading, 
    requestFn 
  };
}
