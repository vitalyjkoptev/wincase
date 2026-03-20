// =====================================================
// FILE: src/composables/useApi.ts
// Axios HTTP client with Sanctum auth
// =====================================================

import axios, { type AxiosInstance, type AxiosError } from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useToast } from 'vue-toastification'
import router from '@/router'

const api: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL || '/api/v1',
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Request interceptor — add Bearer token
api.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

// Response interceptor — handle errors globally
api.interceptors.response.use(
  (response) => response,
  (error: AxiosError<{ message?: string; errors?: Record<string, string[]> }>) => {
    const toast = useToast()
    const status = error.response?.status

    if (status === 401) {
      const auth = useAuthStore()
      auth.logout()
      router.push('/login')
      toast.error('Session expired. Please log in again.')
    } else if (status === 403) {
      toast.error('Access denied.')
    } else if (status === 422) {
      const errors = error.response?.data?.errors
      if (errors) {
        const first = Object.values(errors)[0]?.[0]
        toast.warning(first || 'Validation error.')
      }
    } else if (status === 429) {
      toast.warning('Too many requests. Please wait.')
    } else if (status && status >= 500) {
      toast.error('Server error. Please try again later.')
    }

    return Promise.reject(error)
  }
)

export function useApi() {
  return api
}

// =====================================================
// FILE: src/composables/useAuth.ts
// Auth composable — wraps AuthStore
// =====================================================

// import { computed } from 'vue'
// import { useAuthStore } from '@/stores/auth'
//
// export function useAuth() {
//   const store = useAuthStore()
//
//   return {
//     user: computed(() => store.user),
//     isAuthenticated: computed(() => store.isAuthenticated),
//     isAdmin: computed(() => store.user?.role === 'admin'),
//     isManager: computed(() => ['admin', 'manager'].includes(store.user?.role ?? '')),
//     hasAbility: (ability: string) => {
//       const perms = store.user?.permissions ?? []
//       return perms.includes('*') || perms.includes(ability)
//     },
//     login: store.login,
//     logout: store.doLogout,
//   }
// }

export default api

// ---------------------------------------------------------------
// Аннотация (RU):
// useApi.ts — Axios instance с Bearer token interceptor.
// Глобальная обработка ошибок: 401→logout, 403→denied, 422→validation, 429→rate limit.
// useAuth.ts — composable: user, isAuthenticated, isAdmin, isManager, hasAbility().
// Файл: src/composables/useApi.ts + src/composables/useAuth.ts
// ---------------------------------------------------------------
