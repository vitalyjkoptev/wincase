// =====================================================
// FILE: src/stores/auth.ts
// Auth Store — Pinia
// =====================================================

import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/composables/useApi'

interface User {
  id: number
  name: string
  email: string
  role: string
  phone?: string
  department?: string
  avatar_url?: string
  two_factor_enabled: boolean
  permissions: string[]
}

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('wincase_token'))
  const user = ref<User | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value)

  async function login(email: string, password: string, twoFactorCode?: string) {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.post('/auth/login', { email, password, two_factor_code: twoFactorCode })
      if (data.data.requires_2fa) {
        return { requires2FA: true }
      }
      token.value = data.data.token
      user.value = data.data.user
      localStorage.setItem('wincase_token', data.data.token)
      return { success: true }
    } catch (e: any) {
      error.value = e.response?.data?.message || 'Login failed'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  async function fetchMe() {
    if (!token.value) return
    try {
      const { data } = await api.get('/auth/me')
      user.value = data.data
    } catch {
      logout()
    }
  }

  async function doLogout() {
    try {
      await api.post('/auth/logout')
    } catch { /* ignore */ }
    logout()
  }

  function logout() {
    token.value = null
    user.value = null
    localStorage.removeItem('wincase_token')
  }

  return { token, user, loading, error, isAuthenticated, login, fetchMe, doLogout, logout }
})

// =====================================================
// FILE: src/stores/dashboard.ts
// Dashboard Store
// =====================================================

import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/composables/useApi'

interface DashboardData {
  kpi: Record<string, any>
  leads: Record<string, any>
  finance: Record<string, any>
  ads: Record<string, any>
  social: Record<string, any>
  seo: Record<string, any>
}

export const useDashboardStore = defineStore('dashboard', () => {
  const data = ref<DashboardData | null>(null)
  const loading = ref(false)

  async function load() {
    loading.value = true
    try {
      const { data: res } = await api.get('/dashboard')
      data.value = res.data
    } catch { /* handled by interceptor */ }
    loading.value = false
  }

  async function refreshKpi() {
    try {
      const { data: res } = await api.get('/dashboard/kpi')
      if (data.value) data.value.kpi = res.data
    } catch { /* */ }
  }

  return { data, loading, load, refreshKpi }
})

// =====================================================
// FILE: src/stores/leads.ts
// Leads Store
// =====================================================

import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/composables/useApi'

interface Lead {
  id: number
  name: string
  email: string | null
  phone: string
  source: string
  status: string
  priority: string
  service_type: string
  language: string
  assigned_to: number | null
  notes: string | null
  created_at: string
}

export const useLeadsStore = defineStore('leads', () => {
  const leads = ref<Lead[]>([])
  const total = ref(0)
  const selected = ref<Lead | null>(null)
  const loading = ref(false)
  const filters = ref({ status: '', source: '', search: '' })

  async function load(page = 1) {
    loading.value = true
    try {
      const params: Record<string, any> = { page, per_page: 25 }
      if (filters.value.status) params.status = filters.value.status
      if (filters.value.source) params.source = filters.value.source
      if (filters.value.search) params.search = filters.value.search

      const { data } = await api.get('/leads', { params })
      leads.value = data.data.data
      total.value = data.data.meta.total
    } catch { /* */ }
    loading.value = false
  }

  async function loadDetail(id: number) {
    loading.value = true
    try {
      const { data } = await api.get(`/leads/${id}`)
      selected.value = data.data
    } catch { /* */ }
    loading.value = false
  }

  async function create(payload: Record<string, any>) {
    const { data } = await api.post('/leads', payload)
    return data.data
  }

  async function updateStatus(id: number, status: string) {
    await api.patch(`/leads/${id}`, { status })
    await load()
  }

  async function convert(id: number) {
    await api.post(`/leads/${id}/convert`)
    await loadDetail(id)
  }

  return { leads, total, selected, loading, filters, load, loadDetail, create, updateStatus, convert }
})

// =====================================================
// FILE: src/stores/cases.ts
// Cases Store
// =====================================================

import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/composables/useApi'

export const useCasesStore = defineStore('cases', () => {
  const cases = ref<any[]>([])
  const total = ref(0)
  const selected = ref<any>(null)
  const loading = ref(false)
  const filters = ref({ status: '', service_type: '', assigned_to: '' })

  async function load(page = 1) {
    loading.value = true
    try {
      const params: Record<string, any> = { page, ...filters.value }
      Object.keys(params).forEach(k => { if (!params[k]) delete params[k] })
      const { data } = await api.get('/cases', { params })
      cases.value = data.data.data
      total.value = data.data.meta.total
    } catch { /* */ }
    loading.value = false
  }

  async function loadDetail(id: number) {
    loading.value = true
    try {
      const { data } = await api.get(`/cases/${id}`)
      selected.value = data.data
    } catch { /* */ }
    loading.value = false
  }

  async function create(payload: Record<string, any>) {
    const { data } = await api.post('/cases', payload)
    return data.data
  }

  async function changeStatus(id: number, status: string) {
    await api.post(`/cases/${id}/status`, { status })
    await loadDetail(id)
  }

  async function assign(id: number, userId: number) {
    await api.post(`/cases/${id}/assign`, { assigned_to: userId })
    await loadDetail(id)
  }

  return { cases, total, selected, loading, filters, load, loadDetail, create, changeStatus, assign }
})

// =====================================================
// FILE: src/stores/clients.ts
// Clients Store
// =====================================================

import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/composables/useApi'

export const useClientsStore = defineStore('clients', () => {
  const clients = ref<any[]>([])
  const total = ref(0)
  const selected = ref<any>(null)
  const loading = ref(false)
  const filters = ref({ status: '', search: '', nationality: '' })

  async function load(page = 1) {
    loading.value = true
    try {
      const params: Record<string, any> = { page, per_page: 25, ...filters.value }
      Object.keys(params).forEach(k => { if (!params[k]) delete params[k] })
      const { data } = await api.get('/clients', { params })
      clients.value = data.data.data
      total.value = data.data.meta.total
    } catch { /* */ }
    loading.value = false
  }

  async function loadDetail(id: number) {
    loading.value = true
    try {
      const { data } = await api.get(`/clients/${id}`)
      selected.value = data.data
    } catch { /* */ }
    loading.value = false
  }

  async function create(payload: Record<string, any>) {
    const { data } = await api.post('/clients', payload)
    return data.data
  }

  async function update(id: number, payload: Record<string, any>) {
    await api.put(`/clients/${id}`, payload)
    await loadDetail(id)
  }

  return { clients, total, selected, loading, filters, load, loadDetail, create, update }
})

// ---------------------------------------------------------------
// Аннотация (RU):
// 5 Pinia stores для Vue.js admin panel.
// auth.ts — login (+ 2FA), fetchMe, logout. Token в localStorage.
// dashboard.ts — load() all sections, refreshKpi().
// leads.ts — CRUD, filters (status/source/search), convert to client.
// cases.ts — CRUD, changeStatus, assign, filters.
// clients.ts — CRUD, search, filters (nationality/status).
// Файл: src/stores/auth.ts + dashboard.ts + leads.ts + cases.ts + clients.ts
// ---------------------------------------------------------------
